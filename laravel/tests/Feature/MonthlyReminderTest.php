<?php

use App\Repositories\MemberMembershipStatusRepository;
use App\Models\Member;
use App\Models\MemberMembershipStatus;
use App\Models\User;
use App\Enums\MembershipStatus as EnumsMembershipStatus;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Queue;

beforeEach(function () {
    $this->seed(DatabaseSeeder::class);
});

test('get memberships expiring in 3 months', function () {
    $user = User::factory()->withPersonalTeam()->create();
    // generate members
    $member1 = Member::factory()->for($user)->create();
    $member1->membership_status_id = EnumsMembershipStatus::ACCEPTED->value;
    $member1->save();
    $member2 = Member::factory()->for($user)->create();
    $member2->membership_status_id = EnumsMembershipStatus::ACCEPTED->value;
    $member2->save();
    $member3 = Member::factory()->for($user)->create();
    $member3->membership_status_id = EnumsMembershipStatus::ACCEPTED->value;
    $member3->save();
    $member4 = Member::factory()->for($user)->create();
    $member4->membership_status_id = EnumsMembershipStatus::LAPSED->value;
    $member4->save();
    $member5 = Member::factory()->for($user)->create();
    $member6 = Member::factory()->for($user)->create();
    $member7 = Member::factory()->for($user)->create();
    $member8 = Member::factory()->for($user)->create();
    $member9 = Member::factory()->for($user)->create();

    // Use CarbonImmutable or else the dates keep changing.
    $current = CarbonImmutable::createFromDate(2023, 5, 28);

    $end_date = CarbonImmutable::createFromDate(2023, 6, 30);
    $past_year = $current->subYear(1);
    $future_year = $current->addYear(1);
    $future_month_1 = $current->addMonthWithoutOverflow();
    $future_month_2 = $current->addMonthsWithoutOverflow(2);
    $future_month_3 = $current->addMonthsWithoutOverflow(3);
    $future_month_4 = $current->addMonthsWithoutOverflow(4);

    $data = [
        [
            'member_id' => $member1->id,
            'membership_status_id' => EnumsMembershipStatus::ACCEPTED->value,
            'user_id' => $user->id,
            'from_date' => null,
            'to_date' => $end_date,
        ],
        [
            'member_id' => $member1->id,
            'membership_status_id' => EnumsMembershipStatus::ACCEPTED->value,
            'user_id' => $user->id,
            'from_date' => null,
            'to_date' => $future_month_1,
        ],
        [
            'member_id' => $member3->id,
            'membership_status_id' => EnumsMembershipStatus::ACCEPTED->value,
            'user_id' => $user->id,
            'from_date' => null,
            'to_date' => $future_month_2,
        ],
        // Not Accepted/ Active
        [
            'member_id' => $member4->id,
            'membership_status_id' => EnumsMembershipStatus::ACCEPTED->value,
            'user_id' => $user->id,
            'from_date' => null,
            'to_date' => $future_month_3,
        ],
        // Out of bounds
        [
            'member_id' => $member5->id,
            'membership_status_id' => EnumsMembershipStatus::ACCEPTED->value,
            'user_id' => $user->id,
            'from_date' => null,
            'to_date' => $future_month_4,
        ],
        [
            'member_id' => $member6->id,
            'membership_status_id' => EnumsMembershipStatus::ACCEPTED->value,
            'user_id' => $user->id,
            'from_date' => null,
            'to_date' => $future_year,
        ],
        [
            'member_id' => $member7->id,
            'membership_status_id' => EnumsMembershipStatus::ACCEPTED->value,
            'user_id' => $user->id,
            'from_date' => null,
            'to_date' => $past_year,
        ],
        // Diff membership_status_id
        [
            'member_id' => $member8->id,
            'membership_status_id' => EnumsMembershipStatus::ENDORSED->value,
            'user_id' => $user->id,
            'from_date' => null,
            'to_date' => null,
        ],
        [
            'member_id' => $member9->id,
            'membership_status_id' => EnumsMembershipStatus::LAPSED->value,
            'user_id' => $user->id,
            'from_date' => null,
            'to_date' => null,
        ],
    ];

    foreach ($data as $d) {
        MemberMembershipStatus::create($d);
    }

    $rep = new MemberMembershipStatusRepository();
    $results = $rep->getByStatusIdExpiringIn3Months(EnumsMembershipStatus::ACCEPTED->value, $current->toMutable());

    expect($results->count())->toEqual(4);

    Queue::fake();
    $rep->sendExpiringMembershipReminder($current->toMutable());
    Queue::assertPushed(\Illuminate\Notifications\SendQueuedNotifications::class, 2);
});
