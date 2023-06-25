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

test('user can be created', function () {
    $this->actingAs($user = User::factory()->withPersonalTeam()->create());

    $response = $this->post('/users', [
        'name' => 'Test User',
        'email' => 'test@user.com',
        'password' => 'Pa$$w0rd1234',
    ]);

    expect(User::get())->toHaveCount(2);
    expect(User::latest('id')->first()->name)->toEqual('Test User');
});

test('users can be deleted', function () {
    $this->actingAs($user = User::factory()->withPersonalTeam()->create());

    $user2 = User::factory()->withPersonalTeam()->create();

    $response = $this->delete('/users/'.$user2->id);

    expect(User::get())->toHaveCount(1);
});

test('super user cannot be deleted', function () {
    $this->actingAs($user = User::factory()->withPersonalTeam()->create());

    $response = $this->delete('/users/'.$user->id);

    if ($user->id === 1) {
        expect($user->id)->toEqual(1);
        expect(User::get())->toHaveCount(1);
    }
});
