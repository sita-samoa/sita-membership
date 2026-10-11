<?php

namespace App\Notifications;

use App\Models\Member;
use App\Notifications\Concerns\SkipsDeletedUsers;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExpiringSubReminder extends Notification implements ShouldQueue
{
    use Queueable;
    use SkipsDeletedUsers;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Member $member, public int $days_until_expiry)
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
        return $this->mailChannelFor($notifiable);
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->greeting("Tālofa {$this->member->user->name}!")
            ->line("Your membership will expire in {$this->days_until_expiry}
                days. Please plan for the renewal of your fees. Your
                contribution is greatly appreciated.")
            ->action('View details', route('members.show', $this->member->id))
            ->line('Thank you for your support!');
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
