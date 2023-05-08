<?php

use App\Models\Member;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Queue;

beforeEach(function () {
    $this->seed(DatabaseSeeder::class);
});

test('test that sub reminder sent', function () {
    Queue::fake();

    $this->actingAs($user = User::factory()->withPersonalTeam()->create());
    $member = Member::factory()->for($user)->create();

    $response = $this->put('/members/'.$member->id.'/send-sub-reminder');

    $response->assertStatus(302);
    Queue::assertPushed(\Illuminate\Notifications\SendQueuedNotifications::class, 1);
});

test('test user cannot send a reminder', function () {
    Queue::fake();

    $this->actingAs($user = User::factory()->create());
    $member = Member::factory()->for($user)->create();

    $response = $this->put('/members/'.$member->id.'/send-sub-reminder');

    $response->assertStatus(403);
    Queue::assertPushed(\Illuminate\Notifications\SendQueuedNotifications::class, 0);
});

test('test other roles cannot send a reminder', function (string $role)  {
    Queue::fake();

    $this->actingAs($user = User::factory()->create());
    $member = Member::factory()->for($user)->create();

    $team = Team::first();
    $user->teams()->attach($team, ['role' => $role]);

    $response = $this->put('/members/'.$member->id.'/send-sub-reminder');

    $response->assertStatus(403);
    Queue::assertPushed(\Illuminate\Notifications\SendQueuedNotifications::class, 0);
})->with(['editor', 'executive']);

test('test coordinator can send a reminder', function () {
    Queue::fake();

    $admin = User::factory()->withPersonalTeam()->create();

    $this->actingAs($user = User::factory()->create());
    $member = Member::factory()->for($user)->create();

    $team = Team::first();
    $user->teams()->attach($team, ['role' => 'coordinator']);

    $response = $this->put('/members/'.$member->id.'/send-sub-reminder');

    $response->assertStatus(302);
    Queue::assertPushed(\Illuminate\Notifications\SendQueuedNotifications::class, 1);
});
