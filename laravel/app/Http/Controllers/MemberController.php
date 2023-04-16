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
            'options' => [
                'membership_type_options' => MembershipType::all(['id', 'code', 'title']),
                'gender_options' => Gender::all(['id', 'code', 'title']),
                'title_options' => Title::all(['id', 'code', 'title']),
                // 'membership_status_options' => MembershipStatus::all(['id', 'code', 'title']),
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeSignup(Request $request) : RedirectResponse
    {
        $validated = $request->validate([
            'membership_type_id' => 'required|int|min:1',
        ]);

        $member = $request->user()->members()->create($validated);

        return redirect()->back()->with('member_id', $member->id)->with('success', 'Member Added!');
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
            'membership_type_id' => 'required|int|min:1',
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
    public function update(Request $request, Member $member) : RedirectResponse
    {
        $this->authorize('update', $member);

        $validated = $request->validate([
            'membership_type_id' => 'required|int|min:1',
            'first_name' => 'required_with:membership_type_id|string|max:255',
            'last_name' => 'required_with:membership_type_id|string|max:255',
            // 'title_id' => 'nullable|int',
            'dob' => 'nullable|date',
            'gender_id' => 'required_with:membership_type_id|int|min:1',
            'job_title' => 'required_with:membership_type_id|string|max:255',
            'current_employer' => 'required_with:membership_type_id|string|max:255',
            // 'home_address' => 'string|max:255',
            // 'home_phone' => 'string|max:255',
            // 'home_mobile' => 'string|max:255',
            // 'home_email' => 'string|max:255',
            // 'work_address' => 'string|max:255',
            // 'work_phone' => 'string|max:255',
            // 'work_mobile' => 'string|max:255',
            // 'work_email' => 'string|max:255',
            // 'other_membership' => 'string|max:255',
            // 'note' => 'string|max:255',
        ]);

        $member->update($validated);

        return redirect()->back()->with('success', 'Member Updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member)
    {
        //
    }
}
