<?php

namespace App\Jobs;

use App\Models\Member;
use App\Notifications\InvoiceNotification;
use App\Repositories\MemberInvoicesRepository;
use App\Repositories\MemberRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessInvoice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Member $member)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $rep = new MemberRepository();
        $invoice = $rep->generateInvoice($this->member);
        // Record Invoice details.
        $rep = new MemberInvoicesRepository();
        $memberInvoice = $rep->addInvoice($this->member->id, $invoice);

        // Notify user.
        $this->member->user->notify(new InvoiceNotification($this->member, $memberInvoice));
    }
}
