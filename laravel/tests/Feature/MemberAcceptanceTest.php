<?php

use App\Models\Member;
use App\Models\Team;
use App\Models\User;
use App\Enums\MembershipStatus;
use Carbon\Carbon;

beforeEach(function () {
    $this->seed(DatabaseSeeder::class);
});

test('can mark as accepted', function ($role) {
    User::factory()->withPersonalTeam()->create();
    $team = Team::first();

    $this->actingAs($user = User::factory()->create());
    $team->users()->attach(
        $user,
        ['role' => $role]
    );

    $member = Member::factory()->create();
    $member->membership_status_id = MembershipStatus::ENDORSED->value;
    $member->save();

    $response = $this->put('/members/'.$member->id.'/accept', [
        'financial_year' => Carbon::now()->year,
        'receipt_number' => '111',
    ]);
    $response->assertStatus(302);
})->with(['admin', 'coordinator']);

test('cannot mark as accepted', function ($role) {
    User::factory()->withPersonalTeam()->create();
    $team = Team::first();

    $this->actingAs($user = User::factory()->create());
    $team->users()->attach(
        $user,
        ['role' => $role]
    );

    $member = Member::factory()->create();
    $member->membership_status_id = MembershipStatus::ENDORSED->value;
    $member->save();

    $response = $this->put('/members/'.$member->id.'/accept', [
        'financial_year' => Carbon::now()->year,
        'receipt_number' => '111',
    ]);
    $response->assertStatus(403);
})->with(['editor', 'executive']);

test('cannot mark as accepted with status', function (MembershipStatus $status) {
    User::factory()->withPersonalTeam()->create();
    $team = Team::first();

    $this->actingAs($user = User::factory()->create());
    $team->users()->attach(
        $user,
        ['role' => 'admin']
    );

    $member = Member::factory()->create();
    $member->membership_status_id = $status->value;
    $member->save();

    $response = $this->put('/members/'.$member->id.'/accept', [
        'financial_year' => Carbon::now()->year,
        'receipt_number' => '111',
    ]);
    $response->assertStatus(403);
})->with([
    'DRAFT' => MembershipStatus::DRAFT,
    'SUBMITTED' => MembershipStatus::SUBMITTED,
    'ACCEPTED' => MembershipStatus::ACCEPTED,
    'BANNED' => MembershipStatus::BANNED,
]);

test('that user cannot mark themselves as accepted', function () {
    $this->actingAs($user = User::factory()->create());

    $member = Member::factory()->for($user)->create();

    $response = $this->put('/members/'.$member->id.'/accept', [
        'financial_year' => Carbon::now()->year,
        'receipt_number' => '111',
    ]);
    $response->assertStatus(403);
});
