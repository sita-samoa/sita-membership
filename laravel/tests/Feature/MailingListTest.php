<?php

use App\Models\User;

beforeEach(function () {
    $this->seed(DatabaseSeeder::class);
});

test('it can view the mailing list page', function () {
    $this->actingAs(User::factory()->withPersonalTeam()->create());
    $response = $this->get('/mailing-lists');

    $response->assertStatus(200);
});
