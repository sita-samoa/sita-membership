<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class InvoiceSequenceRepository
{
    public function getNextInvoiceNumber()
    {
        $invoiceNumber = 0;
        DB::transaction(function () use (&$invoiceNumber) {
            $sequence = DB::table('invoice_sequences')->lockForUpdate()->first();
            $invoiceNumber = $sequence->current_number + 1;
            DB::table('invoice_sequences')->update(['current_number' => $invoiceNumber]);
        });

        return $invoiceNumber;
    }
}
