<?php

use App\Models\Team;
use App\Models\User;

/*
 * Demonstrates bug: updating user role without photo field (simulating frontend bug where photoInput.value.files is undefined)
 * should fail or not update the role as expected.
 */
test('updating user role without photo field fails or does not update', function () {
    // Setup: create user and team
    $user = User::factory()->withPersonalTeam()->create();
    $team = Team::first();
    $user->teams()->attach($team, ['role' => 'editor']);

    // Authenticate as admin
    $admin = User::factory()->withPersonalTeam()->create();
    $this->actingAs($admin);

    // Attempt to update user role WITHOUT photo field (simulate frontend bug)
    $response = $this->patch('/users/'.$user->id, [
        'role' => 'admin',
        // 'photo' => missing on purpose
    ]);

    // Reload user
    $user->refresh();

    // The role should be updated even if no photo is provided
    expect($user->hasTeamRole($team, 'admin'))->toBeTrue();
    $response->assertSessionDoesntHaveErrors();
});
