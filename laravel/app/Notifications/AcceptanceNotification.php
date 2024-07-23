<?php

namespace App\Notifications;

use App\Models\Member;
use App\Services\SitaOnlineService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AcceptanceNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Member $member, public SitaOnlineService $sitaOnlineService)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // Have a different message for paid and free memberships.
        $emailMessage = (new MailMessage())
        ->subject('Signup endorsed')
        ->greeting('Tālofa!')
        ->line('A signup request has been Endorsed.');

        $isPaidMembership = $this->sitaOnlineService->isMemberHasPaidMembership($this->member);
        $message = 'Please review for your Acceptance.';

        if ($isPaidMembership) {
            $emailMessage->line('An invoice has also been sent.');
            $emailMessage->line($message);
            $emailMessage->line('Before accepting please ensure:');
            $emailMessage->line('* payment has been collected');
            $emailMessage->line('* receipt number is recorded on SITA Online');
        }
        else {
            $emailMessage->line('No invoice was sent.');
            $emailMessage->line($message);
        }

        $emailMessage->action('View details', route('members.show', $this->member->id));

        return $emailMessage;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
