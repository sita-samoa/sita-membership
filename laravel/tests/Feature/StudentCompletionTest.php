<?php

use App\Models\Member;
use App\Models\MemberMailingPreference;
use App\Models\User;
use App\Repositories\MembershipTypeRepository;
use Database\Seeders\DatabaseSeeder;

beforeEach(function () {
    $this->seed(DatabaseSeeder::class);
});

test('test student membership type requirements', function () {
    // Get student membership id.
    $rep = new MembershipTypeRepository();
    $studentMembershipType = $rep->getByCode('student');

    // Create user and member.
    $user = User::factory()->create();
    $member = Member::factory()->for($user)->create(
        ['membership_type_id' => $studentMembershipType->id]
    );

    // Subscribe to mailing list.
    MemberMailingPreference::create([
        'member_id' => $member->id,
        'mailing_list_id' => 1,
        'subscribed' => true,
    ]);

    $completions = $member->getCompletionsAttribute();
    expect($completions['overall']['status'])->toBeTrue();
});
