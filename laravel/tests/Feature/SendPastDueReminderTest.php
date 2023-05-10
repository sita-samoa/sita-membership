<?php

// test that button appears when sub is past due

use App\Models\Member;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Queue;

beforeEach(function () {
    $this->seed(DatabaseSeeder::class);
});

// @todo test that button appears when sub is past due
// @todo test that button disappears when sub is more than 6 months past due

test('test that reminder cannot be sent if not Lapsed', function ($status_id) {
    Queue::fake();

    $this->actingAs($user = User::factory()->withPersonalTeam()->create());
    $member = Member::factory()->for($user)->create();
    $member->membership_status_id = $status_id;
    $member->save();

    $response = $this->put('/members/'.$member->id.'/send-past-due-sub-reminder');

    $response->assertStatus(403);
    Queue::assertPushed(\Illuminate\Notifications\SendQueuedNotifications::class, 0);
})->with([1,2,3,5,6]);


test('test that reminder sent', function () {
    Queue::fake();

    $this->actingAs($user = User::factory()->withPersonalTeam()->create());
    $member = Member::factory()->for($user)->create();
    $member->membership_status_id = 4;
    $member->save();

    $response = $this->put('/members/'.$member->id.'/send-past-due-sub-reminder');

    $response->assertStatus(302);
    Queue::assertPushed(\Illuminate\Notifications\SendQueuedNotifications::class, 1);
});

test('test user cannot send a reminder', function () {
    Queue::fake();

    $this->actingAs($user = User::factory()->create());
    $member = Member::factory()->for($user)->create();
    $member->membership_status_id = 4;
    $member->save();

    $response = $this->put('/members/'.$member->id.'/send-past-due-sub-reminder');

    $response->assertStatus(403);
    Queue::assertPushed(\Illuminate\Notifications\SendQueuedNotifications::class, 0);
});

test('test other roles cannot send a reminder', function (string $role)  {
    Queue::fake();

    $this->actingAs($user = User::factory()->create());
    $member = Member::factory()->for($user)->create();
    $member->membership_status_id = 4;
    $member->save();

    $team = Team::first();
    $user->teams()->attach($team, ['role' => $role]);

    $response = $this->put('/members/'.$member->id.'/send-past-due-sub-reminder');

    $response->assertStatus(403);
    Queue::assertPushed(\Illuminate\Notifications\SendQueuedNotifications::class, 0);
})->with(['editor', 'executive']);

test('test coordinator can send a reminder', function () {
    Queue::fake();

    $admin = User::factory()->withPersonalTeam()->create();

    $this->actingAs($user = User::factory()->create());
    $member = Member::factory()->for($user)->create();
    $member->membership_status_id = 4;
    $member->save();

    $team = Team::first();
    $user->teams()->attach($team, ['role' => 'coordinator']);

    $response = $this->put('/members/'.$member->id.'/send-past-due-sub-reminder');

    $response->assertStatus(302);
    Queue::assertPushed(\Illuminate\Notifications\SendQueuedNotifications::class, 1);
});
