<?php

namespace App\Notifications;

use App\Models\Member;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AcceptedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Member $member, public bool $isNotifyMember = true)
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
        if ($this->isNotifyMember) {
            return (new MailMessage())
                ->subject('Signup Accepted')
                ->greeting('Tālofa '.$this->member->getFullName().'!')
                ->line('Your signup request has been accepted.')
                ->action('View details', route('members.show', $this->member->id));
        } else {
            return (new MailMessage())
                ->subject('Signup Accepted')
                ->greeting('Tālofa!')
                ->line('A signup request has been accepted for '.$this->member->getFullName().'.')
                ->action('View details', route('members.show', $this->member->id));
        }
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
