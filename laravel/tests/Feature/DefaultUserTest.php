<?php

use App\Models\User;
use Database\Seeders\UsersTableSeeder;

test('test that default user is created', function () {
    $this->seed(UsersTableSeeder::class);
    $this->actingAs($user = User::first());
    $response = $this->get('/dashboard');

    $response->assertStatus(200);
});

// test that default user is created
// test the default user belongs to team id 1
// test that team 1 was created
// test that team 1 is called SITA Online
