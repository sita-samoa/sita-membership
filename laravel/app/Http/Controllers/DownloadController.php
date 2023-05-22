<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use App\Models\MemberSupportingDocument;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadController extends Controller
{
    /**
     * Download supporting document for member.
     */
    public function index(Request $request, Member $member, MemberSupportingDocument $document) : StreamedResponse
    {
        $this->authorize('view', $member);

        return Storage::download($document->file_path, $document->file_name);
    }

}
