<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Member;

class AuditController extends Controller
{
    /**
     * Show the audit log for this member.
     */
    public function index(Request $request, Member $member) : Response
    {
        $this->authorize('view', $member);

        return Inertia::render('Members/Audit', [
            'auditLog' => $member->audits()->with('user')->latest()->get(),
        ]);
    }

}
