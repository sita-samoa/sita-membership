<?php

use App\Models\Member;
use App\Models\User;
use OwenIt\Auditing\Models\Audit;

beforeEach(function () {
    $this->seed(DatabaseSeeder::class);
});

test('test that audit page exists for everyone', function () {
    $this->actingAs($user = User::factory()->withPersonalTeam()->create());
    $member = Member::factory()->for($user)->create();
    $response = $this->get('/members/'.$member->id.'/audit');

    $response->assertStatus(200);
});

// @todo Test that audit is created (requires console auditing turned on)
// https://laravel-auditing.com/guide/general-configuration.html#console-cli-and-jobs
