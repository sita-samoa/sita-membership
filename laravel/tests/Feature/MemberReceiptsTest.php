<?php

use App\Enums\MembershipStatus;
use App\Models\Member;
use App\Models\MemberMembershipStatus;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Carbon\Carbon;

beforeEach(function () {
    $this->seed(DatabaseSeeder::class);
});


test('test no receipts', function () {
    $this->actingAs($user = User::factory()->withPersonalTeam()->create());

    $member = Member::factory($user)->create();
    
    $response = $this->get('/members/'.$member->id);

    $response->assertInertia(function ($assert) {
        $assert->has('statuses', 0);
    });
});

test('test has 1 accepted receipt', function () {
    $this->actingAs($user = User::factory()->withPersonalTeam()->create());

    $member = Member::factory()->for($user)->create();

    // create receipt number.
    $from_date = Carbon::now();

    // This should be returned.
    MemberMembershipStatus::create([
        'member_id' => $member->id,
        'membership_status_id' => MembershipStatus::ACCEPTED->value,
        'user_id' => $user->id,
        'from_date' => $from_date,
        'to_date' => $from_date,
        'receipt_number' => rand(100000, 999999),
    ]);
    
    // This should not be returned.
    MemberMembershipStatus::create([
        'member_id' => $member->id,
        'membership_status_id' => MembershipStatus::LAPSED->value,
        'user_id' => $user->id,
        'from_date' => $from_date,
        'to_date' => $from_date,
        'receipt_number' => 0,
    ]);

    $response = $this->get('/members/'.$member->id);

    $response->assertInertia(function ($assert) {
        $assert->has('statuses', 1);
    });

});
