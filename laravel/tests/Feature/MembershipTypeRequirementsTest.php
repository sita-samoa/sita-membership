<?php

use App\Models\Member;
use App\Models\Team;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Queue;

beforeEach(function () {
    $this->seed(DatabaseSeeder::class);
});

test('user can submit their own membership application', function () {
    $this->actingAs($user = User::factory()->create());
    $member = Member::factory()->for($user)->create();
    $response = $this->put('/members/'.$member->id.'/submit');
    $response->assertStatus(302);
    $response->assertSessionHasNoErrors();
});

