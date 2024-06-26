<?php

use App\Enums\MembershipStatus;
use App\Models\Member;
use Database\Seeders\DatabaseSeeder;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->seed(DatabaseSeeder::class);

    // Test data.
    Member::factory()->count(3)->create(['membership_status_id' => MembershipStatus::ACCEPTED->value]);
    Member::factory()->count(3)->create(['membership_status_id' => MembershipStatus::LAPSED->value]);
    Member::factory()->count(1)->create(['membership_status_id' => MembershipStatus::DRAFT->value]);
});

test('test members list exists', function () {
    $response = $this->get('/members-list');

    $response->assertStatus(200);
});

test('test can filter by member_status_id and search', function () {
    $status = MembershipStatus::ACCEPTED->value;
    $search = 'test';

    Member::factory()->count(1)->create([
        'membership_status_id' => MembershipStatus::ACCEPTED->value,
        'first_name' => $search,
    ]);

    // Get only accepted members
    $response = $this->get('/members-list?search=&membership_status_id='.$status)
        ->assertInertia(
            fn (Assert $page) => $page
                ->component('MembersList/Index')
                ->has('members.data', 4)
                ->where('filters.search', null)
                ->where('filters.membership_status_id', strval($status))
        );

    // Get only lapsed members
    $lapsed = MembershipStatus::LAPSED->value;
    $response = $this->get('/members-list?search=&membership_status_id='.$lapsed)
        ->assertInertia(
            fn (Assert $page) => $page
                ->component('MembersList/Index')
                ->has('members.data', 3)
                ->where('filters.search', null)
                ->where('filters.membership_status_id', strval($lapsed))
        );

    // Get accepted members with search
    $response = $this->get('/members-list?search='.$search.'&membership_status_id='.$status)
        ->assertInertia(
            fn (Assert $page) => $page
                ->component('MembersList/Index')
                ->has('members.data', 1)
                ->has('members.links')
                ->where('members.per_page', 10)
                ->where('filters.search', $search)
                ->where('filters.membership_status_id', strval($status))
        );
});

test('test invalid searches', function ($status) {
    // You can only search for Accepted and Lapsed members. All other types are invalid.
    $search = '';

    $response = $this->get('/members-list?search='.$search.'&membership_status_id='.$status);
    $response->assertInvalid();
})->with([
    'hello',
    MembershipStatus::DRAFT->value,
    MembershipStatus::SUBMITTED->value,
    MembershipStatus::ENDORSED->value,
    MembershipStatus::EXPIRED->value,
    MembershipStatus::BANNED->value,
    MembershipStatus::REJECTED->value,
]);
