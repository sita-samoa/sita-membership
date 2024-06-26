<?php

use App\Models\Team;
use App\Models\User;
use App\Models\Member;
use Database\Seeders\DatabaseSeeder;
use Inertia\Testing\AssertableInertia as Assert;
use App\Enums\MembershipStatus;

beforeEach(function () {
    $this->seed(DatabaseSeeder::class);

    // Test data.
    Member::factory()->count(3)->create(['membership_status_id' => MembershipStatus::ACCEPTED->value]);
    Member::factory()->count(3)->create(['membership_status_id' => MembershipStatus::LAPSED->value]);
    Member::factory()->count(1)->create(['membership_status_id' => MembershipStatus::DRAFT->value]);
});

test('test user without role cannot access', function () {
    $this->actingAs($user = User::factory()->create());
    $response = $this->get('/members');
    $response->assertStatus(403);
});

test('test roles can access and list displayed', function (string $role) {
    // admin can access
    $this->actingAs($admin = User::factory()->withPersonalTeam()->create());
    $team = Team::first();

    // can access
    $user = User::factory()->create();
    $user->teams()->attach($team, ['role' => $role]);
    $this->actingAs($user->fresh());
    $response = $this->get('/members')
        ->assertInertia(
            fn (Assert $page) => $page
                ->component('Members/Index')
                ->has('members.data', 7)
        );

    $response->assertStatus(200);

})->with(['editor', 'executive', 'coordinator']);

test('test can filter by member_status_id and search', function ($role) {
    $status = MembershipStatus::ACCEPTED->value;
    $search = 'test';

    Member::factory()->count(1)->create([
        'membership_status_id' => MembershipStatus::ACCEPTED->value,
        'first_name' => $search,
    ]);

    // admin can access
    $this->actingAs($admin = User::factory()->withPersonalTeam()->create());
    $team = Team::first();

    // can access
    $user = User::factory()->create();
    $user->teams()->attach($team, ['role' => $role]);
    $this->actingAs($user->fresh());

    // Get only accepted members
    $response = $this->get('/members?search=&membership_status_id='.$status)
        ->assertInertia(
            fn (Assert $page) => $page
                ->component('Members/Index')
                ->has('members.data', 4)
                ->where('filters.search', null)
                ->where('filters.membership_status_id', strval($status))
        );

    // Get only lapsed members
    $lapsed = MembershipStatus::LAPSED->value;
    $response = $this->get('/members?search=&membership_status_id='.$lapsed)
        ->assertInertia(
            fn (Assert $page) => $page
                ->component('Members/Index')
                ->has('members.data', 3)
                ->where('filters.search', null)
                ->where('filters.membership_status_id', strval($lapsed))
        );

    // Get accepted members with search
    $response = $this->get('/members?search='.$search.'&membership_status_id='.$status)
        ->assertInertia(
            fn (Assert $page) => $page
                ->component('Members/Index')
                ->has('members.data', 1)
                ->has('members.links')
                ->where('members.per_page', 10)
                ->where('filters.search', $search)
                ->where('filters.membership_status_id', strval($status))
        );
})->with(['editor', 'executive', 'coordinator']);

// @todo test pagination works
