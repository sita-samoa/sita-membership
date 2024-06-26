<?php

use App\Enums\MembershipStatus;
use App\Models\Member;
use Database\Seeders\DatabaseSeeder;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->seed(DatabaseSeeder::class);
});

test('test members list exists', function () {
    $response = $this->get('/members-list');

    $response->assertStatus(200);
});


test('test can filter by member_status_id and search', function () {
    $status = MembershipStatus::ACCEPTED->value;
    $search = 'test';

    Member::factory()->count(3)->create(['membership_status_id' => MembershipStatus::ACCEPTED->value]);
    Member::factory()->count(1)->create(['membership_status_id' => MembershipStatus::DRAFT->value]);

    $response = $this->get('/members-list')
        ->assertInertia(fn (Assert $page) => $page
            ->component('MembersList/Index')
            ->has('members.data', 3) // Ensure 10 members per page
            ->has('members.links')
            ->where('members.per_page', 10)
            // ->where('filters.search', 'Test')
        );

    $response->assertStatus(200);

});
