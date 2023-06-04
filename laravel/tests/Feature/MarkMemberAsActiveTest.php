<?php

use App\Enums\MembershipStatus;
use App\Models\Member;
use App\Models\Team;
use App\Models\User;

beforeEach(function () {
    $this->seed(DatabaseSeeder::class);
});

test('test can mark as active', function ($role) {
    User::factory()->withPersonalTeam()->create();
    $team = Team::first();

    $this->actingAs($user = User::factory()->create());
    $team->users()->attach(
        $user,
        ['role' => $role]
    );

    $member = Member::factory()->create();
    $member->membership_status_id = MembershipStatus::LAPSED->value;
    $member->save();

    $response = $this->put('/members/'.$member->id.'/activate');
    $response->assertStatus(302);
})->with(['admin', 'coordinator']);

test('test cannot mark as active', function ($role) {
    User::factory()->withPersonalTeam()->create();
    $team = Team::first();

    $this->actingAs($user = User::factory()->create());
    $team->users()->attach(
        $user,
        ['role' => $role]
    );

    $member = Member::factory()->create();
    $member->membership_status_id = MembershipStatus::LAPSED->value;
    $member->save();

    $response = $this->put('/members/'.$member->id.'/activate');
    $response->assertStatus(403);
})->with(['editor', 'executive']);

test('test that user cannot mark themselves as active', function () {
    $this->actingAs($user = User::factory()->create());

    $member = Member::factory()->for($user)->create();

    $response = $this->put('/members/'.$member->id.'/activate');
    $response->assertStatus(403);
});
