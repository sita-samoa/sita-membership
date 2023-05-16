<?php

use App\Models\Team;
use App\Models\User;
use Database\Seeders\UsersTableSeeder;

beforeEach(function () {
    $this->seed(UsersTableSeeder::class);
});

test('test that default user is created', function () {
    $this->actingAs($user = User::first());
    $response = $this->get('/dashboard');

    $response->assertStatus(200);
});

test('test that team 1 was created', function () {
    $teams = Team::get();
    expect(count($teams))->toEqual(1);
});

test('test that team 1 is called SITA Online', function () {
    $team = Team::first();
    expect($team->name)->toEqual('SITA Online');
});

test('test the default user belongs to team 1', function () {
    $user = User::first();

    expect($user->fresh()->ownedTeams)->toHaveCount(1);
    expect($user->fresh()->ownedTeams()->latest('id')->first()->name)->toEqual('SITA Online');
});
