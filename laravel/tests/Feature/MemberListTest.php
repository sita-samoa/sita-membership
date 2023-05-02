<?php

use App\Models\Team;
use App\Models\User;

test('test user without role cannot access', function () {
    $this->actingAs($user = User::factory()->create());
    $response = $this->get('/members');
    $response->assertStatus(403);
});

test('test roles can access', function (string $role) {
    // admin can access
    $this->actingAs($admin = User::factory()->withPersonalTeam()->create());
    $team = Team::first();

    // can access
    $user = User::factory()->create();
    $user->teams()->attach($team, array('role' => $role));
    $this->actingAs($user->fresh());
    $response = $this->get('/members');

    $response->assertStatus(200);
})->with(['editor', 'executive', 'coordinator']);

// @todo test list displayed
// @todo test filter works
// @todo test pagination works
