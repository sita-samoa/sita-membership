<?php

use App\Repositories\MemberMembershipStatusRepository;
use App\Models\Member;
use App\Models\MemberMembershipStatus;
use App\Models\User;
use App\Enums\MembershipStatus as EnumsMembershipStatus;
use App\Repositories\MemberRepository;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Queue;

beforeEach(function () {
    $this->seed(DatabaseSeeder::class);
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
    $member5->membership_status_id = EnumsMembershipStatus::ACCEPTED->value;
    $member5->save();
    $member6 = Member::factory()->for($user)->create();
    $member6->membership_status_id = EnumsMembershipStatus::ACCEPTED->value;
    $member6->save();
    $member7 = Member::factory()->for($user)->create();
    $member7->membership_status_id = EnumsMembershipStatus::ACCEPTED->value;
    $member7->save();
    $member8 = Member::factory()->for($user)->create();
    $member8->membership_status_id = EnumsMembershipStatus::ACCEPTED->value;
    $member8->save();
    $member9 = Member::factory()->for($user)->create();
    $member9->membership_status_id = EnumsMembershipStatus::ACCEPTED->value;
    $member9->save();

    // Use CarbonImmutable or else the dates keep changing.
    $this->current = $current = CarbonImmutable::createFromDate(2023, 5, 28);

    $end_date = CarbonImmutable::createFromDate(2023, 6, 30);
    $past_year = $current->subYear(1);
    $future_year = $current->addYear(1);
    $future_month_1 = $current->addMonthWithoutOverflow();
    $future_month_2 = $current->addMonthsWithoutOverflow(2);
    $future_month_3 = $current->addMonthsWithoutOverflow(3);
    $future_month_4 = $current->addMonthsWithoutOverflow(4);

    $this->data = [
        [
            'member_id' => $member1->id,
            'membership_status_id' => EnumsMembershipStatus::ACCEPTED->value,
            'user_id' => $user->id,
            'from_date' => null,
            'to_date' => $end_date,
        ],
        // Duplicate entry
        [
            'member_id' => $member1->id,
            'membership_status_id' => EnumsMembershipStatus::ACCEPTED->value,
            'user_id' => $user->id,
            'from_date' => null,
            'to_date' => $future_month_1,
        ],
        [
            'member_id' => $member2->id,
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

    foreach ($this->data as $d) {
        MemberMembershipStatus::create($d);
    }
});

test('get memberships expiring in 3 months', function () {
    $rep = new MemberMembershipStatusRepository();
    $results = $rep->getExpiringIn3Months($this->current->toMutable());

    expect($results->count())->toEqual(5);
});

test('send expiring membership reminders', function () {
    Queue::fake();

    $rep = new MemberMembershipStatusRepository();
    $rep->sendExpiringMembershipReminders($this->current->toMutable());

    Queue::assertPushed(\Illuminate\Notifications\SendQueuedNotifications::class, 3);
});

test('get memberships expired less than 6 months', function () {
    $current = $this->current->addMonthsWithoutOverflow(3);
    $rep = new MemberMembershipStatusRepository();
    $results = $rep->getExpiredLessThan6Months($current->toMutable());

    expect($results->count())->toEqual(5);
});

test('send past due sub reminders', function () {
    Queue::fake();

    $current = $this->current->addMonthsWithoutOverflow(3);
    $rep = new MemberMembershipStatusRepository();
    $rep->sendPastDueSubReminders($current->toMutable());

    // Only one of the 5 items is marked Lapsed and will be notified.
    Queue::assertPushed(\Illuminate\Notifications\SendQueuedNotifications::class, 1);
});

test('membership marked as lapsed and recorded', function () {
    $current = $this->current->addMonthsWithoutOverflow(3);
    $rep = new MemberMembershipStatusRepository();

    $statues = MemberMembershipStatus::get();
    expect($statues->count())->toEqual(count($this->data));

    $rep2 = new MemberRepository();
    $members = $rep2->getByMembershipStatusId(EnumsMembershipStatus::LAPSED->value);
    expect($members->count())->toEqual(1);

    $rep->markAsLapsed($current->toMutable());

    // Members marked lapsed.
    $members = $rep2->getByMembershipStatusId(EnumsMembershipStatus::LAPSED->value);
    expect($members->count())->toEqual(4);

    // Membership records created.
    $statues = MemberMembershipStatus::get();
    expect($statues->count())->toEqual(13);

});
