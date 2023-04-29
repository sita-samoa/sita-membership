<?php

use App\Models\User;
use Database\Seeders\UsersTableSeeder;

test('test that default user is created', function () {
    $this->seed(UsersTableSeeder::class);
    $this->actingAs($user = User::first());
    $response = $this->get('/dashboard');

    $response->assertStatus(200);
});

test('test the default user belongs to team id 1', function () {
// test that team 1 was created
// test that team 1 is called SITA Online
    $this->seed(UsersTableSeeder::class);
    $user = User::first();

    expect($user->fresh()->ownedTeams)->toHaveCount(1);
    expect($user->fresh()->ownedTeams()->latest('id')->first()->id)->toEqual(1);
    expect($user->fresh()->ownedTeams()->latest('id')->first()->name)->toEqual('SITA Online');
});
