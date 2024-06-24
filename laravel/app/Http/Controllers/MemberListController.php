<?php

namespace App\Http\Controllers;

use App\Enums\MembershipStatus;
use App\Models\Member;
use App\Repositories\MemberRepository;
use Inertia\Inertia;
use Illuminate\Http\Request;

class MemberListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $search = $request->input('search');
        $membership_status_id = MembershipStatus::ACCEPTED->value;

        $rep = new MemberRepository();
        // $members = $rep->filterMembers($membership_status_id, $search);
        $members = $rep->filterMembers($membership_status_id, $search)
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

        return Inertia::render('MemberList/Index', [
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
