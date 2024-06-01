<?php

use App\Enums\MembershipStatus;
use App\Models\Member;
use App\Models\MembershipType;
use App\Services\SitaOnlineService;
use Database\Seeders\DatabaseSeeder;

beforeEach(function () {
    $this->seed(DatabaseSeeder::class);

    // set prices - full 100, affiliate 50, associate 50, student 0
    $fullMembership = MembershipType::find(1);
    $associateMembership = MembershipType::find(2);
    $affiliateMembership = MembershipType::find(3);
    $studentMembership = MembershipType::find(4);
    $fellowMembership = MembershipType::find(5);

    $fullMembership->setAnnualCost(100);
    $fullMembership->save();
    $associateMembership->setAnnualCost(50);
    $associateMembership->save();
    $affiliateMembership->setAnnualCost(50);
    $affiliateMembership->save();
    $studentMembership->setAnnualCost(0);
    $studentMembership->save();
    $fellowMembership->setAnnualCost(0);
    $fellowMembership->save();

    # Total owing
    $member = Member::factory()->create();
    $member->setMembershipStatus(MembershipStatus::LAPSED);
    $member->setMembershipType($fullMembership->id);
    $member->save();

    $member = Member::factory()->create();
    $member->setMembershipStatus(MembershipStatus::LAPSED);
    $member->setMembershipType($fullMembership->id);
    $member->save();

    $member = Member::factory()->create();
    $member->setMembershipStatus(MembershipStatus::LAPSED);
    $member->setMembershipType($fullMembership->id);
    $member->save();

    $member = Member::factory()->create();
    $member->setMembershipStatus(MembershipStatus::LAPSED);
    $member->setMembershipType($associateMembership->id);
    $member->save();

    $member = Member::factory()->create();
    $member->setMembershipStatus(MembershipStatus::LAPSED);
    $member->setMembershipType($affiliateMembership->id);
    $member->save();

    # Total collected
    $member = Member::factory()->create();
    $member->setMembershipStatus(MembershipStatus::ACCEPTED);
    $member->setMembershipType($fullMembership->id);
    $member->save();

    $member = Member::factory()->create();
    $member->setMembershipStatus(MembershipStatus::ACCEPTED);
    $member->setMembershipType($associateMembership->id);
    $member->save();

    $member = Member::factory()->create();
    $member->setMembershipStatus(MembershipStatus::ACCEPTED);
    $member->setMembershipType($affiliateMembership->id);
    $member->save();

    $member = Member::factory()->create();
    $member->setMembershipStatus(MembershipStatus::ACCEPTED);
    $member->setMembershipType($studentMembership->id);
    $member->save();

    $member = Member::factory()->create();
    $member->setMembershipStatus(MembershipStatus::ACCEPTED);
    $member->setMembershipType($fellowMembership->id);
    $member->save();
});

test('test correct owing funds calculation', function () {
    $service = new SitaOnlineService();
    $funds = $service->getOutstandingPayment();

    expect($funds)->toBe(400.0);
});

test('test correct total funds calculation', function () {
    $service = new SitaOnlineService();
    $funds = $service->getTotalCollected();

    expect($funds)->toBe(200.0);
});
