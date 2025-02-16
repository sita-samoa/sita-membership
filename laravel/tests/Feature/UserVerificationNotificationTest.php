<?php

use App\Models\User;
use App\Services\SitaOnlineService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Queue;

beforeEach(function () {
    // Create 5 unverified users with different creation dates
    $this->users = [
        User::factory()->create([
            'email_verified_at' => null,
            'created_at' => Carbon::now()->subDays(2), // Created 2 days ago
        ]),
        User::factory()->create([
            'email_verified_at' => null,
            'created_at' => Carbon::now()->subDays(2), // Created 2 days ago
        ]),
        User::factory()->create([
            'email_verified_at' => null,
            'created_at' => Carbon::now()->subDays(2), // Created 2 days ago
        ]),
        User::factory()->create([
            'email_verified_at' => null,
            'created_at' => Carbon::now()->subWeek(), // Created 1 week ago
        ]),
        User::factory()->create([
            'email_verified_at' => null,
            'created_at' => Carbon::now()->subMonths(1), // Created 1 month ago
        ]),
    ];
});

it('notifies users created 2 days ago', function () {
    // Fake the Queue so no jobs are actually pushed to the queue
    Queue::fake();

    $service = new SitaOnlineService();
    $service->sendUnverifiedAccountReminderForTwoDays();

    Queue::assertPushed(\Illuminate\Notifications\SendQueuedNotifications::class, 3);
});

it('notifies users created more than 1 week ago', function () {
    // Fake the Queue so no jobs are actually pushed to the queue
    Queue::fake();

    $service = new SitaOnlineService();
    $service->sendUnverifiedAccountReminderForMoreThanTwoDays();

    Queue::assertPushed(\Illuminate\Notifications\SendQueuedNotifications::class, 2);
});
