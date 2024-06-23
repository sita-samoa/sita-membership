<?php

use App\Enums\MembershipStatus;
use App\Models\Member;
use App\Models\Team;
use App\Models\User;
use App\Repositories\MemberMembershipStatusRepository;
use App\Repositories\MemberRepository;
use Carbon\Carbon;

beforeEach(function () {
    $this->seed(DatabaseSeeder::class);
});

test('test cannot set membership status', function () {
    User::factory()->withPersonalTeam()->create();

    $this->actingAs($user = User::factory()->create());

    $member = Member::factory()->for($user)->create();

    $rep = new MemberRepository();
    $new_status = MembershipStatus::ACCEPTED->value;
    $rep->updateMembershipStatus($member, MembershipStatus::SUBMITTED);

    $response = $this->put('/members/'.$member->id.'/membership-status/'.$new_status, []);

    $response->assertStatus(403);

    $rep2 = new MemberMembershipStatusRepository();
    $statuses = $rep2->getByMemberIdAndStatusId($member->id, $new_status, 10);

    expect($statuses)->toHaveCount(0);

    // Cannot set with invalid id.
    $response = $this->put('/members/'.$member->id.'/membership-status/1000', []);

    $response->assertStatus(404);

    // Cannot set with letter.
    $response = $this->put('/members/'.$member->id.'/membership-status/a', []);

    $response->assertStatus(404);
});

test('test can set membership status', function ($role) {
    User::factory()->withPersonalTeam()->create();
    $team = Team::first();

    $this->actingAs($user = User::factory()->create());
    $team->users()->attach(
        $user,
        ['role' => $role]
    );
    $member = Member::factory()->for($user)->create();

    $rep = new MemberRepository();
    $new_status = MembershipStatus::DRAFT->value;
    $rep->updateMembershipStatus($member, MembershipStatus::SUBMITTED);

    $response = $this->put('/members/'.$member->id.'/membership-status/'.$new_status, []);

    $response->assertStatus(302);

    // Assert that no validation errors are present.
    $response->assertValid();
    $response->assertSessionHas('success');

    $rep2 = new MemberMembershipStatusRepository();
    $statuses = $rep2->getByMemberIdAndStatusId($member->id, $new_status, 10);

    expect($statuses)->toHaveCount(1);
})->with(['admin', 'coordinator']);

test('test can set membership status as accepted', function ($role) {
    User::factory()->withPersonalTeam()->create();
    $team = Team::first();

    $this->actingAs($user = User::factory()->create());
    $team->users()->attach(
        $user,
        ['role' => $role]
    );
    $member = Member::factory()->for($user)->create();

    $rep = new MemberRepository();
    $new_status = MembershipStatus::ACCEPTED->value;
    $rep->updateMembershipStatus($member, MembershipStatus::SUBMITTED);

    $response = $this->put('/members/'.$member->id.'/membership-status/'.$new_status, [
        'financial_year' => Carbon::now()->year,
        'receipt_number' => '111',
    ]);

    $response->assertStatus(302);

    // Assert that no validation errors are present.
    $response->assertValid();
    $response->assertSessionHas('success');

    $rep2 = new MemberMembershipStatusRepository();
    $statuses = $rep2->getByMemberIdAndStatusId($member->id, $new_status, 10);

    expect($statuses)->toHaveCount(1);
})->with(['admin', 'coordinator']);

test('test cannot set membership status as accepted', function ($role) {
    User::factory()->withPersonalTeam()->create();
    $team = Team::first();

    $this->actingAs($user = User::factory()->create());
    $team->users()->attach(
        $user,
        ['role' => $role]
    );
    $member = Member::factory()->for($user)->create();
    // Set to Full membership so that is requires a receipt number.
    $member->membership_type_id = 1;

    $rep = new MemberRepository();
    $new_status = MembershipStatus::ACCEPTED->value;
    $rep->updateMembershipStatus($member, MembershipStatus::SUBMITTED);

    $response = $this->put('/members/'.$member->id.'/membership-status/'.$new_status, [
        'financial_year' => 1999,
        'receipt_number' => '',
    ]);

    // Assert that validation errors are present.
    $response->assertInvalid(['financial_year', 'receipt_number']);

    $response = $this->put('/members/'.$member->id.'/membership-status/'.$new_status, [
        'financial_year' => 2000,
        'receipt_number' => '',
    ]);

    // Assert that no validation errors are present.
    $response->assertValid(['financial_year']);
    $response->assertInvalid(['receipt_number']);
})->with(['admin', 'coordinator']);
