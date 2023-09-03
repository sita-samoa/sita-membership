<?php

namespace App\Notifications;

use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use LaravelDaily\Invoices\Invoice as FacadesInvoice;

class Invoice extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Member $member, public FacadesInvoice $invoice)
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
        return (new MailMessage())
            ->subject('SITA Invoice - '.Carbon::now()->format('d/m/Y'))
            ->greeting("Tālofa {$this->member->user->name}!")
            ->line('An invoice has been issued to you regarding your SITA membership subscription.')
            ->action('View details', $this->invoice->url());
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
