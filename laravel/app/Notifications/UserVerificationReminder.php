<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserVerificationReminder extends Notification implements ShouldQueue
{
    use Queueable;

    public $user;

    // Constructor to pass the user data to the notification
    public function __construct(User $user)
    {
        $this->user = $user;
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
        return (new MailMessage())
            ->subject('Reminder: Please Verify Your Email')
            ->line('Hi '.$this->user->name.',')
            ->line('It looks like you haven’t verified your email address yet.')
            ->action('Verify Email', route('verification.verify', [
                'id' => $this->user->id,
                'hash' => sha1($this->user->email),
            ]))
            ->line('Thank you for using our application!');
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
