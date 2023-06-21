<?php

namespace App\Http\Controllers;

use App\Models\MailingList;
use App\Models\Member;
use App\Models\MemberMailingPreference;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MailingListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        //
        $this->authorize('viewAny', Member::class);

        $lists = MailingList::get();
        $req_id = $request->get('id');
        $id = $req_id != null ? $req_id : MemberMailingPreference::first()->id;
        $member_preferences = MemberMailingPreference::where('mailing_list_id', $id)
            ->where('subscribed', true)->get('member_id');

        $all_members = Member::whereIn('id', $member_preferences);

        $all_emails = implode('; ', $all_members->pluck('home_email')->map(function ($item) {
            return (string) $item;
        })->toArray());
        $members = $all_members->with(['mailingLists' => function ($query) {
            $query->orderByPivot('updated_at', 'desc');
        }])->paginate(10);

        return Inertia::render('MailingLists/Index', [
            'mailingLists' => $lists,
            'members' => $members,
            'mailingId' => (int) $id,
            'all_emails' => $all_emails,
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
