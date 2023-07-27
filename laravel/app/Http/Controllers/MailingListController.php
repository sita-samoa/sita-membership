<?php

namespace App\Http\Controllers;

use App\Models\MailingList;
use App\Models\Member;
use App\Models\MembershipType;
use App\Repositories\MailingListRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MailingListController extends Controller
{
    public function __construct(public MailingListRepository $rep = new MailingListRepository())
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        //
        $this->authorize('viewAny', Member::class);

        $lists = MailingList::get();
        $req_id = $request->get('id');
        $id = $req_id != null ? $req_id : MailingList::first()->id;
        $member_types = MembershipType::all();

        $all_members = $this->rep->getAllSubscribedMembers($id);

        $membership_type_id = $request->get('membershipTypeId');
        if ($membership_type_id != null && $membership_type_id != 0) {
            $all_members = $all_members->where('membership_type_id', $membership_type_id);
        }

        $membership_status_id = $request->get('membershipStatusId');
        if ($membership_status_id != null && $membership_status_id != 0) {
            $all_members = $all_members->where('membership_status_id', $membership_status_id);
        }

        $all_emails = $this->rep->getAllEmails($all_members);
        $members = $all_members->with(['mailingLists' => function ($query) {
            $query->orderByPivot('updated_at', 'desc');
        }])->paginate(10);

        $fromDate = Carbon::now()->startOfMonth()->toDateString();
        $tillDate = Carbon::now()->endOfMonth()->toDateString();
        $fromDateLastMonth = Carbon::now()->subMonth()->startOfMonth()->toDateString();
        $tillDateLastMonth = Carbon::now()->subMonth()->endOfMonth()->toDateString();

        $last_month_subs = $this->rep->getSubStatusCount($id, true, $fromDateLastMonth, $tillDateLastMonth);
        $this_month_subs = $this->rep->getSubStatusCount($id, true, $fromDate, $tillDate);
        $this_month_unsubs = $this->rep->getSubStatusCount($id, false, $fromDate, $tillDate);
        $last_month_unsubs = $this->rep->getSubStatusCount($id, false, $fromDateLastMonth, $tillDateLastMonth);

        return Inertia::render('MailingLists/Index', [
            'mailingLists' => $lists,
            'membershipTypes' => $member_types,
            'members' => $members,
            'membershipTypeId' => (int) $membership_type_id ?? 0,
            'membershipStatusId' => (int) $membership_status_id ?? 0,
            'mailingId' => (int) $id,
            'allEmails' => $all_emails,
            'subData' => [
                'month_subs' => $this_month_subs ?? 0,
                'month_unsubs' => $this_month_unsubs ?? 0,
                'last_month_subs' => $last_month_subs ?? 0,
                'last_month_unsubs' => $last_month_unsubs ?? 0,
            ],
        ]);
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
    public function show(MailingList $mailingList)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MailingList $mailingList)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MailingList $mailingList)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MailingList $mailingList)
    {
        //
    }
}
