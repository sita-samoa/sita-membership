<?php

use App\Models\Member;
use App\Models\MemberWorkExperience;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;

beforeEach(function () {
    $this->seed(DatabaseSeeder::class);
});

test('add member experience', function () {
    $this->actingAs($user = User::factory()->create());
    $member = Member::factory()->create();

    // add work experience
    $response = $this->post('/member-work-experiences', [
        'member_id' => $member->id,
        'organisation' => '1',
        'position' => '1',
        'responsibilities' => '1',
        'from_date' => '2022-12-05',
        'to_date' => '2023-12-05',
        'is_current' => false,
    ]);

    $response->assertStatus(302);
    // Assert that no validation errors are present.
    $response->assertValid();
    $response->assertSessionHas('success');
});

test('cannot add more than 1 record with is_current set to true', function () {
    $this->actingAs($user = User::factory()->create());
    $member = Member::factory()->create();

    // add work experience
    $response = $this->post('/member-work-experiences', [
        'member_id' => $member->id,
        'organisation' => '1',
        'position' => '1',
        'responsibilities' => '1',
        'from_date' => '2022-12-05',
        'to_date' => '2023-12-05',
        'is_current' => true,
    ]);
    // Assert that no validation errors are present.
    $response->assertValid();
    $response->assertSessionMissing('error');

    // add another record with is_current set to true
    $response = $this->post('/member-work-experiences', [
        'member_id' => $member->id,
        'organisation' => '2',
        'position' => '2',
        'responsibilities' => '2',
        'from_date' => '2022-12-05',
        'to_date' => '2023-12-05',
        'is_current' => true,
    ]);

    $response->assertStatus(302);
    // Assert that no validation errors are present.
    $response->assertValid();
    $response->assertSessionHas('error');
});

test('cannot update second work experience to set is_current as true', function () {
    $this->actingAs($user = User::factory()->create());
    $member = Member::factory()->create();

    // add work experiences
    $response = $this->post('/member-work-experiences', [
        'member_id' => $member->id,
        'organisation' => '1',
        'position' => '1',
        'responsibilities' => '1',
        'from_date' => '2022-12-05',
        'to_date' => '2023-12-05',
        'is_current' => true,
    ]);

    // Assert that no validation errors are present.
    $response->assertValid();
    $response->assertSessionMissing('error');

    $response = $this->post('/member-work-experiences', [
        'member_id' => $member->id,
        'organisation' => '2',
        'position' => '2',
        'responsibilities' => '2',
        'from_date' => '2022-12-05',
        'to_date' => '2023-12-05',
        'is_current' => false,
    ]);

    // Assert that no validation errors are present.
    $response->assertValid();
    $response->assertSessionMissing('error');

    // update second work experience to set is_current as true
    $work_experience = MemberWorkExperience::where('organisation', '2')->first();
    $response = $this->put('/member-work-experiences/'.$work_experience->id, [
        'member_id' => $member->id,
        'organisation' => '2',
        'position' => '2',
        'responsibilities' => '2',
        'from_date' => '2022-12-05',
        'to_date' => '2023-12-05',
        'is_current' => true,
    ]);

    $response->assertStatus(302);
    // Assert that no validation errors are present.
    $response->assertValid();
    $response->assertSessionHas('error');
});

test('to_date comes after from_date', function () {

    $this->actingAs($user = User::factory()->create());
    $member = Member::factory()->create();

    $response = $this->post('/member-work-experiences', [
        'member_id' => $member->id,
        'organisation' => '1',
        'position' => '1',
        'responsibilities' => '1',
        'from_date' => '2023-12-05',
        'to_date' => '2022-12-05',
        'is_current' => false,
    ]);

    $response->assertStatus(302);
    // Assert that validation errors are present.
    $response->assertInvalid(['to_date']);
});

test('to_date is required when is_current is false', function () {

    $this->actingAs($user = User::factory()->create());
    $member = Member::factory()->create();

    $response = $this->post('/member-work-experiences', [
        'member_id' => $member->id,
        'organisation' => '1',
        'position' => '1',
        'responsibilities' => '1',
        'from_date' => '2023-12-05',
        'is_current' => false,
    ]);

    $response->assertStatus(302);
    // Assert that validation errors are present.
    $response->assertInvalid(['to_date']);
});

test('to_date is not required when is_current is true', function () {

    $this->actingAs($user = User::factory()->create());
    $member = Member::factory()->create();

    $response = $this->post('/member-work-experiences', [
        'member_id' => $member->id,
        'organisation' => '1',
        'position' => '1',
        'responsibilities' => '1',
        'from_date' => '2023-12-05',
        'is_current' => true,
    ]);

    $response->assertStatus(302);
    // Assert that validation errors are present.
    $response->assertValid();
});
