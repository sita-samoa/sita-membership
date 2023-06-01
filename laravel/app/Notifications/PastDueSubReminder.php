<?php

namespace App\Notifications;

use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PastDueSubReminder extends Notification implements ShouldQueue
{
    use Queueable;

    protected function getDays() {
        $current = Carbon::now();
        return $current->diffInDays($this->grace_period);
    }

    /**
     * Create a new notification instance.
     */
    public function __construct(public Member $member, public Carbon $grace_period)
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
            ->line('Your SITA sub is now due.')
            ->line("If we dont hear back from you by
                {$this->grace_period->toFormattedDateString()}
                ({$this->getDays()} days), your membership
                will expire.")
            ->line('Please make arrangements to pay your sub. Your
                contribution is greatly appreciated.')
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
