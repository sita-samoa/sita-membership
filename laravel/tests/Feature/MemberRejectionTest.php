<?php

use App\Models\Member;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Queue;

beforeEach(function () {
    $this->seed(DatabaseSeeder::class);
});

test('test notification sent when rejected', function () {
    Queue::fake();
    $admin = User::factory()->withPersonalTeam()->create();
    $team = Team::first();

    $this->actingAs($user = User::factory()->create());
    $team->users()->attach(
        $user,
        ['role' => 'admin']
    );

    $member = Member::factory()->create();

    $response = $this->put('/members/'.$member->id.'/reject', ['reason' => 'Test']);
    $response->assertStatus(302);

    // There should be one user receiving notification.
    Queue::assertPushed(\Illuminate\Notifications\SendQueuedNotifications::class, 1);
});

test('test can reject', function ($role) {
    $admin = User::factory()->withPersonalTeam()->create();
    $team = Team::first();

    $this->actingAs($user = User::factory()->create());
    $team->users()->attach(
        $user,
        ['role' => $role]
    );

    $member = Member::factory()->create();

    $response = $this->put('/members/'.$member->id.'/reject', ['reason' => 'Test']);
    $response->assertStatus(302);
})->with(['admin', 'executive']);

test('test cannot reject', function ($role) {
    $admin = User::factory()->withPersonalTeam()->create();
    $team = Team::first();

    $this->actingAs($user = User::factory()->create());
    $team->users()->attach(
        $user,
        ['role' => $role]
    );

    $member = Member::factory()->create();

    $response = $this->put('/members/'.$member->id.'/reject', ['reason' => 'Test']);
    $response->assertStatus(403);
})->with(['editor', 'coordinator']);

test('test that user cannot reject their own application', function () {
    $this->actingAs($user = User::factory()->create());

    $member = Member::factory()->for($user)->create();

    $response = $this->put('/members/'.$member->id.'/reject', ['reason' => 'Test']);
    $response->assertStatus(403);
});
