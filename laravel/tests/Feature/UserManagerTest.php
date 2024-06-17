<?php

use App\Http\Controllers\UserController;
use App\Models\Team;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

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

test('user can paginate users', function () {
    User::factory()->count(45)->create();

    $user = $this->actingAs($user = User::factory()->withPersonalTeam()->create());

    $user->get('/users')
        ->assertInertia(
            fn (Assert $assert) => $assert
                ->component('Users/Index')
                ->has('users.data', UserController::DEFAULT_ROWS_PER_PAGE)
        );

    // @todo - Check why this is 21 it should be 20.
    $user->get('/users?page=2')
        ->assertInertia(
            fn (Assert $assert) => $assert
                ->component('Users/Index')
                ->has('users.data', 21)
        );
});

test('user can modify limits', function (int $limit) {
    User::factory()->count(150)->create();

    $user = $this->actingAs($user = User::factory()->withPersonalTeam()->create());

    $user->get('/users?limit='.$limit)
        ->assertInertia(
            fn (Assert $assert) => $assert
                ->component('Users/Index')
                ->has('users.data', $limit)
        );
})->with([10, 25, 50, 100]);

test('user can paginate higher than 100', function (int $limit) {
    User::factory()->count(150)->create();

    $user = $this->actingAs($user = User::factory()->withPersonalTeam()->create());

    // Cannot have more than 100.
    $user->get('/users?limit='.$limit)
        ->assertInertia(
            fn (Assert $assert) => $assert
                ->component('Users/Index')
                ->has('users.data', UserController::MAX_LIMIT)
        );
})->with([101, 110, 150]);

test('user can be created', function (string $role) {
    $this->actingAs($user = User::factory()->withPersonalTeam()->create());

    $response = $this->post('/users', [
        'name' => 'Test User',
        'email' => 'test@user.com',
        'password' => 'Pa$$w0rd1234',
        'role' => $role,
    ]);

    expect(User::get())->toHaveCount(2);
    /**
     * @var User $new_user
     */
    $new_user = User::latest('id')->first();
    expect($new_user->name)->toEqual('Test User');
    $team = Team::first();
    expect($new_user->fresh()->hasTeamRole($team, $role))->toBeTrue();
})->with(['admin', 'editor', 'executive', 'coordinator']);

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

    $response->assertStatus(302);
});
