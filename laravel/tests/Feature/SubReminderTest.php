<?php

use App\Models\Member;
use App\Models\User;
use Illuminate\Support\Facades\Queue;

beforeEach(function () {
    $this->seed(DatabaseSeeder::class);
});

test('test that sub reminder sent', function () {
    Queue::fake();

    $this->actingAs($user = User::factory()->withPersonalTeam()->create());
    $member = Member::factory()->for($user)->create();

    $response = $this->put('/members/'.$member->id.'/send-sub-reminder');

    $response->assertStatus(302);
    Queue::assertPushed(\Illuminate\Notifications\SendQueuedNotifications::class, 1);
});
