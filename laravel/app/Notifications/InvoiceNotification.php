<?php

namespace App\Notifications;

use App\Models\Member;
use App\Models\MemberInvoices;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoiceNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Member $member, public MemberInvoices $invoice)
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
        $url = route('members.invoices.download.index', [
            'invoice' => $this->invoice->id,
            'member' => $this->member->id,
        ]);

        $formattedAmount = number_format($this->invoice->amount, 2);
        $formattedAmount = 'WST$ '.$formattedAmount;

        return (new MailMessage())
            ->subject('Invoice '.$this->invoice->invoice_number.' from Samoa Information Technology Association for '.$this->member->getFullName())
            ->greeting("Tālofa {$this->member->getFullName()}!")
            ->line('Here\'s invoice '.$this->invoice->invoice_number.' for '.$formattedAmount.'.')
            ->line('The amount outstanding of '.$formattedAmount.' is due on '.Carbon::parse($this->invoice->pay_before_date)->format('d M Y').'.')
            ->action('View details', $url)
            ->line('If you have any questions, please let us know.');
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
