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

        $auditLog = $member->audits()->with('user')->latest()->get();

        // @todo Sort the merged collection by 'created_at' desc.
        foreach($member->qualifications()->cursor() as $q) {
            $auditLog = $auditLog->merge($q->audits()->with('user')->get());
        }
        foreach ($member->supportingDocuments()->cursor() as $q) {
            $auditLog = $auditLog->merge($q->audits()->with('user')->get());
        }
        foreach ($member->workExperiences()->cursor() as $q) {
            $auditLog = $auditLog->merge($q->audits()->with('user')->get());
        }
        foreach ($member->referees()->cursor() as $q) {
            $auditLog = $auditLog->merge($q->audits()->with('user')->get());
        }

        return Inertia::render('Members/Audit', [
            'auditLog' => $auditLog,
        ]);
    }

}
