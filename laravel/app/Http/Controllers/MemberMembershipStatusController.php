<?php

namespace App\Http\Controllers;

use App\Enums\MembershipStatus;
use App\Models\Member;
use App\Models\MemberMembershipStatus;
use Illuminate\Http\Request;

class MemberMembershipStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(MemberMembershipStatus $memberMembershipStatus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MemberMembershipStatus $memberMembershipStatus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Member $member, MemberMembershipStatus $memberMembershipStatus)
    {
        // @TODO - Check that only coordinator can update this field.
        $this->authorize('update', $member);

        $validated = $request->validate([
            'membership_status_id' => 'int',

            // Additional validation of accepted is selected.
            'financial_year' => $request['membership_status_id'] == MembershipStatus::ACCEPTED->value ? 'required|int|min:2000' : '',
            'receipt_number' => $request['membership_status_id'] == MembershipStatus::ACCEPTED->value ? 'required|string' : '',
        ]);

        $member->membership_status_id = $validated['membership_status_id'];
        $member->save();

        return redirect()->back()->with('success', 'Membership Status Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MemberMembershipStatus $memberMembershipStatus)
    {
        //
    }
}
