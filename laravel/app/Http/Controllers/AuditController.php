<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use OwenIt\Auditing\Models\Audit;

class AuditController extends Controller
{
    /**
     * Show the audit log for this member.
     */
    public function index(Request $request, Member $member): Response
    {
        $this->authorize('view', $member);

        // Crazy way to get relationship audits sorted.
        $auditIds = $member->audits()->get('id');

        foreach ($member->qualifications()->cursor() as $q) {
            $auditIds = $auditIds->merge($q->audits()->get('id'));
        }
        foreach ($member->supportingDocuments()->cursor() as $q) {
            $auditIds = $auditIds->merge($q->audits()->get('id'));
        }
        foreach ($member->workExperiences()->cursor() as $q) {
            $auditIds = $auditIds->merge($q->audits()->get('id'));
        }
        foreach ($member->referees()->cursor() as $q) {
            $auditIds = $auditIds->merge($q->audits()->get('id'));
        }
        $ids = array_column($auditIds->all(), 'id');

        return Inertia::render('Members/Audit', [
            'auditLog' => Audit::whereIn('id', $ids)
                ->with('user:id,name')
                ->orderBy('created_at', 'desc')
                ->paginate(10)
                ->withQueryString(),
            'member_id' => $member->id,
        ]);
    }
}
