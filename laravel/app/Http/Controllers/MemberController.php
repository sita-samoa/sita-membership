<?php

namespace App\Http\Controllers;

use App\Models\Gender;
use App\Models\Member;
use App\Models\MembershipStatus;
use App\Models\MembershipType;
use App\Models\Title;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\Inertia;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() : Response
    {
        // @todo - remove this in prod
        return Inertia::render('Dashboard', [
            //...
        ]);
    }

    /**
     * Show the form for sign up of new member.
     */
    public function signup() : Response
    {
        return Inertia::render('Members/Signup', [
            'member_id' => -1,
            'options' => [
                'membership_type_options' => MembershipType::all(['id', 'code', 'title']),
                'gender_options' => Gender::all(['id', 'code', 'title']),
                'title_options' => Title::all(['id', 'code', 'title']),
                'membership_status_options' => MembershipStatus::all(['id', 'code', 'title']),
            ]
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
    public function store(Request $request) : RedirectResponse
    {
        $validated = $request->validate([

            'membership_type_id' => 'required|int',

        ]);

        $request->user()->members()->create($validated);

        return redirect(route('members.signup'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Member $member)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Member $member)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Member $member)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member)
    {
        //
    }
}
