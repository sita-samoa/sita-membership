<?php

use App\Enums\MembershipStatus;
use App\Enums\MembershipType;
use App\Models\Member;
use App\Models\Team;
use App\Models\User;
use App\Notifications\InvoiceNotification;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    $this->seed(DatabaseSeeder::class);
});

test('test invoice notification sent', function ($membership_type_id) {
    Notification::fake();
    $admin = User::factory()->withPersonalTeam()->create();
    $team = Team::first();

    $this->actingAs($user = User::factory()->create());
    $team->users()->attach(
        $user,
        ['role' => 'executive']
    );

    $member = Member::factory()->create();
    $member->membership_status_id = MembershipStatus::SUBMITTED;
    $member->membership_type_id = $membership_type_id->value;
    $member->save();

    $response = $this->put('/members/'.$member->id.'/endorse');

    $response->assertStatus(302);

    Notification::assertSentTo(
        [$member->user],
        InvoiceNotification::class
    );
})->with([
    'FULL' => MembershipType::FULL,
    'ASSOCIATED' => MembershipType::ASSOCIATED,
    'AFFILIATE' => MembershipType::AFFILIATE,
]);

test('test invoice notification not sent', function ($membership_type_id) {
    Notification::fake();
    $admin = User::factory()->withPersonalTeam()->create();
    $team = Team::first();

    $this->actingAs($user = User::factory()->create());
    $team->users()->attach(
        $user,
        ['role' => 'executive']
    );

    $member = Member::factory()->create();
    $member->membership_status_id = MembershipStatus::SUBMITTED;
    $member->membership_type_id = $membership_type_id->value;
    $member->save();

    $response = $this->put('/members/'.$member->id.'/endorse');

    $response->assertStatus(302);

    // Invoice is not sent
    Notification::assertNotSentTo(
        [$member->user],
        InvoiceNotification::class
    );
})->with([
    'STUDENT' => MembershipType::STUDENT,
    'FELLOW' => MembershipType::FELLOW,
]);
