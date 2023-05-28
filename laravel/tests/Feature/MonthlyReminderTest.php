<?php

use App\Http\Repositories\MemberMembershipStatusRepository;
use App\Models\Member;
use App\Models\MemberMembershipStatus;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Queue;

beforeEach(function () {
    $this->seed(DatabaseSeeder::class);
});

test('get memberships expiring in 3 months', function () {
    $user = User::factory()->withPersonalTeam()->create();
    // generate members
    $member1 = Member::factory()->for($user)->create();
    $member2 = Member::factory()->for($user)->create();
    $member3 = Member::factory()->for($user)->create();
    $member4 = Member::factory()->for($user)->create();
    $member5 = Member::factory()->for($user)->create();
    $member6 = Member::factory()->for($user)->create();
    $member7 = Member::factory()->for($user)->create();
    $member8 = Member::factory()->for($user)->create();
    $member9 = Member::factory()->for($user)->create();

    $current = CarbonImmutable::createFromDate(2023, 5, 28);

    $end_date = CarbonImmutable::createFromDate(2023, 6, 30);
    $past_year = $current->subYear(1);
    $future_year = $current->addYear(1);
    $future_month_1 = $current->addMonthWithoutOverflow();
    $future_month_2 = $current->addMonthsWithoutOverflow(2);
    $future_month_3 = $current->addMonthsWithoutOverflow(3);
    $future_month_4 = $current->addMonthsWithoutOverflow(4);

    // $member = Member::first();

    $data = [
        [
            'member_id' => $member1->id,
            'membership_status_id' => 4,
            'user_id' => $user->id,
            'from_date' => null,
            'to_date' => $end_date->toMutable(),
        ],
        [
            'member_id' => $member1->id,
            'membership_status_id' => 4,
            'user_id' => $user->id,
            'from_date' => null,
            'to_date' => $future_month_1->toMutable(),
        ],
        [
            'member_id' => $member3->id,
            'membership_status_id' => 4,
            'user_id' => $user->id,
            'from_date' => null,
            'to_date' => $future_month_2->toMutable(),
        ],
        [
            'member_id' => $member4->id,
            'membership_status_id' => 4,
            'user_id' => $user->id,
            'from_date' => null,
            'to_date' => $future_month_3->toMutable(),
        ],
        // Out of bounds
        [
            'member_id' => $member5->id,
            'membership_status_id' => 4,
            'user_id' => $user->id,
            'from_date' => null,
            'to_date' => $future_month_4->toMutable(),
        ],
        [
            'member_id' => $member6->id,
            'membership_status_id' => 4,
            'user_id' => $user->id,
            'from_date' => null,
            'to_date' => $future_year,
        ],
        [
            'member_id' => $member7->id,
            'membership_status_id' => 4,
            'user_id' => $user->id,
            'from_date' => null,
            'to_date' => $past_year,
        ],
        // Diff membership_status_id
        [
            'member_id' => $member8->id,
            'membership_status_id' => 3,
            'user_id' => $user->id,
            'from_date' => null,
            'to_date' => null,
        ],
        [
            'member_id' => $member9->id,
            'membership_status_id' => 5,
            'user_id' => $user->id,
            'from_date' => null,
            'to_date' => null,
        ],
    ];

    foreach ($data as $d) {
        MemberMembershipStatus::create($d);
    }

    $rep = new MemberMembershipStatusRepository();
    // $results = $rep->getByStatusIdExpiringIn(4, $current->toMutable(), $future_month_3->toMutable());
    // $results = $rep->getByStatusIdExpiringIn3Months(4, $current->toMutable());
    $results = $rep->getByStatusIdExpiringIn3Months(4,null,-1);

    expect($results->count())->toEqual(4);

    Queue::fake();
    $rep->sendExpiringMembershipReminder($current->toMutable());
    Queue::assertPushed(\Illuminate\Notifications\SendQueuedNotifications::class, 3);
});
