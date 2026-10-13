<?php

use App\Enums\MembershipStatus;
use App\Models\Member;
use App\Models\MemberMembershipStatus;
use App\Models\MemberWorkExperience;
use App\Models\User;
use App\Notifications\AcceptedNotification;
use App\Notifications\SubReminder;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Notifications\SendQueuedNotifications;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Testing\Fakes\MailFake;
use Laravel\Jetstream\Features;

test('user accounts can be deleted', function () {
    $this->actingAs($user = User::factory()->create());

    $response = $this->delete('/user', [
        'password' => 'password',
    ]);

    $this->assertSoftDeleted($user);
})->skip(function () {
    return ! Features::hasAccountDeletionFeatures();
}, 'Account deletion is not enabled.');

test('correct password must be provided before account '
    .'can be deleted', function () {
        $this->actingAs($user = User::factory()->create());

        $response = $this->delete('/user', [
            'password' => 'wrong-password',
        ]);

        expect($user->fresh())->not->toBeNull();
    })->skip(function () {
        return ! Features::hasAccountDeletionFeatures();
    }, 'Account deletion is not enabled.');
