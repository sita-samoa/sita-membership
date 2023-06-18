<?php

namespace App\Http\Controllers;

use App\Models\MailingList;
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
        $lists = MailingList::get();
        $ml = MailingList::find(1);
        if ($request->query('id') != null) {
            $id = $request->query('id');
            $ml = MailingList::where('id', $id)->first();
            if ($ml == null) {
                abort(404);
            }
        }
        $members = [];
        foreach ($ml->members as $member) {
            if ($member->pivot->subscribed) {
                array_push($members, $member);
            }
        }

        return Inertia::render('MailingLists/Index', [
            'mailingLists' => $lists,
            'members' => $members,
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
