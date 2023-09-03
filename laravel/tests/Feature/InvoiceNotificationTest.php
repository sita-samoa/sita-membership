<?php

use App\Enums\MembershipStatus;
use App\Enums\MembershipType;
use App\Models\Member;
use App\Models\Team;
use App\Models\User;
use App\Notifications\Invoice;
use Carbon\Carbon;
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
        ['role' => 'coordinator']
    );

    $member = Member::factory()->create();
    $member->membership_status_id = MembershipStatus::ENDORSED;
    $member->membership_type_id = $membership_type_id->value;
    $member->save();

    $response = $this->put(
        '/members/'.$member->id.'/accept',
        ['financial_year' => Carbon::now()->year, 'receipt_number' => '121']
    );
    $response->assertStatus(302);

    Notification::assertSentTo(
        [$member->user],
        Invoice::class
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
        ['role' => 'coordinator']
    );

    $member = Member::factory()->create();
    $member->membership_status_id = MembershipStatus::ENDORSED;
    $member->membership_type_id = $membership_type_id->value;
    $member->save();

    $response = $this->put(
        '/members/'.$member->id.'/accept',
        ['financial_year' => Carbon::now()->year, 'receipt_number' => '111']
    );
    $response->assertStatus(302);

    Notification::assertNothingSent();
})->with([
    'STUDENT' => MembershipType::STUDENT,
    'FELLOW' => MembershipType::FELLOW,
]);
