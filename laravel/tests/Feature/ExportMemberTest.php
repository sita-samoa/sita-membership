<?php

use App\Models\Member;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;

beforeEach(function () {
    $this->seed(DatabaseSeeder::class);
});

test('members exported', function () {
    $this->actingAs($user = User::factory()->withPersonalTeam()->create());
    $response = $this->get('/members/export');

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    $response->assertHeader('Content-Disposition', 'attachment; filename=members.xlsx');
});

test('export member with null title_id', function () {
    $this->actingAs($user = User::factory()->withPersonalTeam()->create());
    $member = Member::factory()->create(['title_id' => null]);
    $response = $this->get('/members/export');

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    $response->assertHeader('Content-Disposition', 'attachment; filename=members.xlsx');
});
