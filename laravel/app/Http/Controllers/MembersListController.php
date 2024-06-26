<?php

namespace App\Http\Controllers;

use App\Enums\MembershipStatus;
use App\Models\Member;
use App\Repositories\MemberRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MembersListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // @TODO add authorization.

        // Only allow Accepted and Lapsed membership status ids.
        $validatedData = $request->validate([
            'search' => 'nullable|string|max:255',
            'membership_status_id' => 'nullable|integer|in:4,5|exists:membership_statuses,id',
        ]);

        $membership_status_ids = [
            MembershipStatus::ACCEPTED->value,
            MembershipStatus::LAPSED->value,
        ];

        $search = $validatedData['search'] ?? '';
        $membership_status_id = $validatedData['membership_status_id'] ?? '';

        if ($membership_status_id) {
            $membership_status_ids = [$membership_status_id];
        }

        $rep = new MemberRepository();
        $members = $rep->filterMembers($membership_status_ids, $search)
            ->select([
                'first_name',
                'last_name',
                'current_employer',
                'job_title',
                'membership_type_id',
                'title_id',
                'membership_status_id',
            ]);

        $pagedMembers = $members
            ->with('membershipType', 'title', 'membershipStatus')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('MembersList/Index', [
            'filters' => $request->only('membership_status_id', 'search'),
            'members' => $pagedMembers,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Member $member)
    {
        //
    }
}
