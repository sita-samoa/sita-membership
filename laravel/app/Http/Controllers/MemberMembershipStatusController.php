<?php

namespace App\Http\Controllers;

use App\Enums\MembershipStatus as MembershipStatusEnum;
use App\Models\Member;
use App\Models\MemberMembershipStatus;
use App\Models\MembershipStatus;
use App\Repositories\MemberRepository;
use App\Services\SitaOnlineService;
use Illuminate\Http\Request;

class MemberMembershipStatusController extends Controller
{
    protected $sitaOnlineService;

    public function __construct(SitaOnlineService $sitaOnlineService)
    {
        $this->sitaOnlineService = $sitaOnlineService;
    }

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
    public function update(Request $request, Member $member, MembershipStatus $membershipStatus)
    {
        // Only coordinator can update this field.
        $this->authorize('updateMembershipStatus', $member);

        $isFreeMembership = $this->sitaOnlineService->isMemberHasFreeMembership($member);

        $isAccepted = $membershipStatus->id == MembershipStatusEnum::ACCEPTED->value;

        if ($isFreeMembership) {
            $validated = $request->validate([
                // Additional validation of accepted is selected.
                'financial_year' => $isAccepted ? 'required|int|min:2000' : '',
                'receipt_number' => $isAccepted ? 'nullable|string' : '',
            ]);
        } else {
            $validated = $request->validate([
                // Additional validation of accepted is selected.
                'financial_year' => $isAccepted ? 'required|int|min:2000' : '',
                'receipt_number' => $isAccepted ? 'required|string' : '',
            ]);
        }

        $rep = new MemberRepository();
        if ($isAccepted) {
            $rep->accept(
                $member,
                $request->user(),
                $validated['financial_year'],
                $validated['receipt_number'] ?? ''
            );
        } else {
            $status = MembershipStatusEnum::fromInt($membershipStatus->id);
            $rep->updateMembershipStatus($member, $status);
            $rep->recordAction($member, $request->user());
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
