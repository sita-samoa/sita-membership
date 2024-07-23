<?php

namespace App\Repositories;

use App\Models\MemberInvoices;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\InvalidCastException;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;
use LaravelDaily\Invoices\Invoice;

class MemberInvoicesRepository extends Repository
{
    /**
     * @return MemberInvoices
     *
     * @throws MassAssignmentException
     * @throws InvalidArgumentException
     * @throws InvalidCastException
     */
    public function addInvoice(int $member_id, Invoice $invoice)
    {
        $memberInvoice = new MemberInvoices();
        $invoiceDate = Carbon::createFromFormat('d/m/Y', $invoice->getDate());
        $payBeforeDate = Carbon::createFromFormat('d/m/Y', $invoice->getPayUntilDate());
        $path = $invoice->url();
        // Correct the path.
        $path = str_replace('/storage/', '/invoices/', $path);

        $memberInvoice->fill([
            'invoice_status_id' => 1,
            'invoice_date' => $invoiceDate,
            'invoice_number' =>  $invoice->getSerialNumber(),
            'amount' => $invoice->total_amount,
            'pay_before_date' => $payBeforeDate,
        ]);
        $memberInvoice->member_id = $member_id;
        // $memberInvoice->title = $invoice->getCustomData()
        $memberInvoice->file_name = $invoice->filename;
        $memberInvoice->file_path = $path;
        $memberInvoice->file_size = $path ? Storage::size($path) : null;

        $memberInvoice->save();

        return $memberInvoice;
    }
}
