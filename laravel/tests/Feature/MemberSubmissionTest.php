<?php

use App\Models\Member;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Queue;

beforeEach(function () {
    $this->seed(DatabaseSeeder::class);
});

test('test cannot submit application when incomplete', function () {
    $this->actingAs($user = User::factory()->create());

    $member = Member::factory()->create();

    $response = $this->put('/members/'.$member->id.'/submit');
    $response->assertStatus(403);
});

test('test group admin can submit application when incomplete', function () {
    $admin = User::factory()->withPersonalTeam()->create();
    $team = Team::first();

    $this->actingAs($user = User::factory()->create());
    $team->users()->attach(
        $user,
        ['role' => 'admin']
    );

    $member = Member::factory()->create();

    $response = $this->put('/members/'.$member->id.'/submit');
    $response->assertStatus(302);
});

// @todo - find a way to not repeat above code above down
test('test notification sent when submitted', function () {
    Queue::fake();
    $admin = User::factory()->withPersonalTeam()->create();
    $team = Team::first();

    $this->actingAs($user = User::factory()->create());
    $team->users()->attach(
        $user,
        ['role' => 'admin']
    );

    $member = Member::factory()->create();

    $response = $this->put('/members/'.$member->id.'/submit');
    $response->assertStatus(302);

    // There should be two users receiving notifications. Site admin and Group admin.
    Queue::assertPushed(\Illuminate\Notifications\SendQueuedNotifications::class, 2);
});

test('@todo - test that user can submit their own application', function () {
    // Need to get factories working first
    $this->actingAs($user = User::factory()->create());
    $member = Member::factory()->for($user)->create();
    $member->qualifications()->create([
        'qualification' => '1',
        'year_attained' => '1',
        'institution' => '1',
        'country_id' => 1,
    ]);
    $member->supportingDocuments()->create([
        'title' => '1',
    ]);
    $member->workExperiences()->create([
        'organisation' => '1',
        'position' => '1',
        'responsibilities' => '1',
        'from_date' => '2022-12-05',
        'to_date' => '2023-12-05',
    ]);
    $member->referees()->create([
        'name' => '1',
        'organisation' => '1',
        'phone' => '1',
        'email' => '1@test.com',
    ]);

    $response = $this->put('/members/'.$member->id.'/submit');
    $response->assertStatus(302);
})->skip();

// @todo test that another user cannot submit another users application
// @todo test that editor and executive cannot submit application
