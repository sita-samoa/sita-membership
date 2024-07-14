<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MemberInvoices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InvoiceDownloadController extends Controller
{
    /**
     * Download invoice for member.
     */
    public function index(Request $request, Member $member, MemberInvoices $invoice): StreamedResponse
    {
        $this->authorize('view', $member);

        return Storage::download($invoice->file_path, $invoice->file_name);
    }
}
