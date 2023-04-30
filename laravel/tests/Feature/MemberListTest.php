<?php

use App\Models\User;

// test /members endpoint exists
test('test that members list end point exists', function () {
    // $this->actingAs($user = User::factory()->create());
    // $response->assertStatus(403);
    $this->actingAs($user = User::factory()->withPersonalTeam()->create());
    $response = $this->get('/members');
    $response->assertStatus(200);

});

// test list displayed
// test filter works
// test pagination works
// test permissions work
