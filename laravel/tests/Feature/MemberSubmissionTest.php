<?php

use App\Models\Member;
use App\Models\User;

beforeEach(function () {
    $this->seed(DatabaseSeeder::class);
});

test('test cannot submit application when incomplete', function () {
    $this->actingAs($user = User::factory()->create());

    $member = Member::factory()->create();

    $response = $this->put('/members/' . $member->id . '/submit');
    $response->assertStatus(403);
});

// @todo test that user can submit their own application
// @todo test that another user cannot submit another users application
// @todo test that editor and executive cannot submit application
