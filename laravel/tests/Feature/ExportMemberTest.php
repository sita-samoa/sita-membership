<?php

use App\Models\User;

test('members exported', function () {
    $this->actingAs($user = User::factory()->withPersonalTeam()->create());
    $response = $this->get('/members/export');

    $response->assertStatus(200);
});
