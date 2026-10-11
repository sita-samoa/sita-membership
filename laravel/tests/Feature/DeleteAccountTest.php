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

test('correct password must be provided before account can be deleted', function () {
    $this->actingAs($user = User::factory()->create());

    $response = $this->delete('/user', [
        'password' => 'wrong-password',
    ]);

    expect($user->fresh())->not->toBeNull();
})->skip(function () {
    return ! Features::hasAccountDeletionFeatures();
}, 'Account deletion is not enabled.');

test('deleting an account preserves its membership records', function () {
    $this->seed(DatabaseSeeder::class);

    $this->actingAs($user = User::factory()->withPersonalTeam()->create());
    $teamId = $user->ownedTeams()->first()->id;
    $tokenId = $user->createToken('account deletion test')->accessToken->id;
    $member = Member::factory()->for($user)->create([
        'membership_status_id' => MembershipStatus::ACCEPTED->value,
        'membership_type_id' => 1,
    ]);
    $workExperience = MemberWorkExperience::create([
        'member_id' => $member->id,
        'organisation' => 'SITA',
        'position' => 'Member',
        'responsibilities' => 'Membership activities',
        'from_date' => '2025-01-01',
        'to_date' => '2025-12-31',
    ]);
    $history = MemberMembershipStatus::create([
        'member_id' => $member->id,
        'membership_status_id' => MembershipStatus::ACCEPTED->value,
        'user_id' => $user->id,
        'from_date' => now(),
    ]);

    $response = $this->delete('/user', [
        'password' => 'password',
    ]);

    $response->assertRedirect();
    $this->assertSoftDeleted($user);
    $this->assertDatabaseHas('members', [
        'id' => $member->id,
        'user_id' => $user->id,
    ]);
    $this->assertDatabaseHas('member_work_experiences', [
        'id' => $workExperience->id,
        'member_id' => $member->id,
    ]);
    $this->assertDatabaseHas('member_membership_statuses', [
        'id' => $history->id,
        'user_id' => $user->id,
    ]);
    $this->assertDatabaseMissing('personal_access_tokens', ['id' => $tokenId]);
    $this->assertDatabaseMissing('teams', ['id' => $teamId]);

    expect(User::find($user->id))->toBeNull()
        ->and(User::withTrashed()->find($user->id)->trashed())->toBeTrue()
        ->and($member->fresh()->user)->toBeNull()
        ->and($history->fresh()->user->is($user))->toBeTrue();
})->skip(function () {
    return ! Features::hasAccountDeletionFeatures();
}, 'Account deletion is not enabled.');

test('queued membership notifications check deletion at delivery time', function (string $notificationClass, bool $deleteRecipient) {
    Queue::fake();
    $mail = new class(app('mail.manager')) extends MailFake
    {
        public int $deliveries = 0;

        public function send($view, array $data = [], $callback = null)
        {
            $this->deliveries++;

            return parent::send($view, $data, $callback);
        }
    };
    Mail::swap($mail);
    $this->seed(DatabaseSeeder::class);

    $user = User::factory()->create();
    $member = Member::factory()->for($user)->create();
    $user->notify(new $notificationClass($member));

    Queue::assertPushed(SendQueuedNotifications::class, 1);
    $payload = serialize(Queue::pushed(SendQueuedNotifications::class)->sole());

    if ($deleteRecipient) {
        $user->delete();
    }

    $job = unserialize($payload);
    expect($job->channels)->toBe(['mail']);
    $job->handle(app(ChannelManager::class));

    expect($mail->deliveries)->toBe($deleteRecipient ? 0 : 1);
})->with([AcceptedNotification::class, SubReminder::class])->with([true, false]);

test('membership notifications initiated after deletion select no channels', function () {
    $this->seed(DatabaseSeeder::class);

    $user = User::factory()->create();
    $member = Member::factory()->for($user)->create();
    $notification = new SubReminder($member);
    $user->delete();

    expect($notification->via($user))->toBe([]);
});
