<?php

use App\Models\Member;
use App\Models\User;

test('test cannot submit application when incomplete', function () {
    $this->seed(DatabaseSeeder::class);
    $this->actingAs($user = User::factory()->create());
    $member = Member::factory()->create();

    $response = $this->put('/members/' . $member->id . '/submit');
    $response->assertStatus(403);
});

// test that user can submit their own application
// test that another user cannot submit another users application
// test that editor and executive cannot submit application
