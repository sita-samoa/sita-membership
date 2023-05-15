<?php

namespace App\Notifications;

use App\Models\Member;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PastDueSubReminder extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Member $member)
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
        return (new MailMessage)
            ->subject("Past Due Sub Reminder")
            ->greeting("Tālofa {$this->member->user->name}!")
            ->line('Your SITA sub is now past due.')
            ->line('This is a reminder to pay your SITA Sub. Your
                contribution is greatly appreciated.')
            ->line('If we dont hear back from you by [date], your membership
                will expire and no longer be a member of SITA.')
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
