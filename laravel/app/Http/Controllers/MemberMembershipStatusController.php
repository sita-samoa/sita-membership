<?php

namespace App\Http\Controllers;

use App\Enums\MembershipStatus;
use App\Models\Member;
use App\Models\MemberMembershipStatus;
use App\Repositories\MemberRepository;
use App\Repositories\MembershipTypeRepository;
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

        $mtRep = new MembershipTypeRepository();
        $memberships = $mtRep->getFreeMemberships();
        $isFreeMembership = false;

        $memberRep = new MemberRepository();

        foreach ($memberships as $freeMembership) {
            if ($member->membershipType->id == $freeMembership->id) {
                $isFreeMembership = true;
                break;
            }
        }

        $isAccepted = $request['membership_status_id'] == MembershipStatus::ACCEPTED->value;

        if ($isFreeMembership) {
            $validated = $request->validate([
                'membership_status_id' => 'int',

                // Additional validation of accepted is selected.
                'financial_year' => $isAccepted ? 'required|int|min:2000' : '',
                'receipt_number' => $isAccepted ? 'nullable|string' : '',
            ]);
        }
        else {
            $validated = $request->validate([
                'membership_status_id' => 'int',

                // Additional validation of accepted is selected.
                'financial_year' => $isAccepted ? 'required|int|min:2000' : '',
                'receipt_number' => $isAccepted ? 'required|string' : '',
            ]);
        }

        if ($isAccepted) {
            $memberRep->accept(
                $member,
                $request->user(),
                $validated['financial_year'],
                $validated['receipt_number'] ?? ''
            );
        }
        else {
            $status = MembershipStatus::fromInt($validated['membership_status_id']);
            $memberRep->updateMembershipStatus($member, $status);
        }

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
