<?php

use App\Models\Team;
use App\Models\User;

test('user without role cannot access', function () {
    $this->actingAs($user = User::factory()->create());
    $response = $this->get('/users');
    $response->assertStatus(403);
});

test('cannot access with role', function (string $role) {
    // admin can access
    $this->actingAs($admin = User::factory()->withPersonalTeam()->create());
    $team = Team::first();

    // can access
    $user = User::factory()->create();
    $user->teams()->attach($team, ['role' => $role]);
    $this->actingAs($user->fresh());
    $response = $this->get('/users');

    $response->assertStatus(403);
})->with(['editor', 'executive', 'coordinator']);

test('can access with role', function (string $role) {
    // admin can access
    $this->actingAs($admin = User::factory()->withPersonalTeam()->create());
    $team = Team::first();

    // can access
    $user = User::factory()->create();
    $user->teams()->attach($team, ['role' => $role]);
    $this->actingAs($user->fresh());
    $response = $this->get('/users');

    $response->assertStatus(200);
})->with(['admin']);

// @todo test list displayed
// @todo test filter works
// @todo test pagination works