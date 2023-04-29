<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Member;
use App\Models\MembershipType;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() : Response
    {
        // @todo - remove this in prod
        return Inertia::render('Members/Index', [
            'members' => Member::with('membershipType', 'title', 'membershipApplicationStatus')->get(),
        ]);
    }

    /**
     * Show the form for sign up of new member.
     */
    public function signup(Request $request)
    {
        // If already had a member, redirect - unless they have a permission.
        $user = $request->user();
        $team = Team::first();

        if (!$user->hasTeamPermission($team, 'member:create_many')) {
            $member = Member::where('user_id', $user->id)->first();

            if ($member) {
                return redirect()->route('members.signup.index', $member->id);
            }
        }

        return Inertia::render('Members/Signup', [
            'options' => [
                'membership_type_options' => MembershipType::all(['id', 'code', 'title']),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeSignup(Request $request) : RedirectResponse
    {
        $validated = $request->validate([
            'membership_type_id' => 'required|int|min:1',
            // 'membership_status_id' => 'required|int|min:1',
        ]);

        $member = new Member();
        $member->fill($validated);
        // set none fillable fields
        $member->membership_status_id = 1;
        $member->user_id = $request->user()->id;
        $member->save();

        return redirect()->route('members.signup.index', $member->id)
            ->with('data', [
                'id' => $member->id,
            ])
            ->with('success', 'Member Added');
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
        // unused
        return redirect(route('members.signup'));
    }

    /**
     * Submit member application.
     */
    public function submit(Member $member) : RedirectResponse
    {
        $this->authorize('submit', $member);

        $member->membership_application_status_id = 2;
        $member->save();

        return redirect()->back()->with('success', 'Application Submitted');
    }
    /**
     * Endorse member application.
     */
    public function endorse(Member $member) : RedirectResponse
    {
        $this->authorize('endorse', $member);

        $member->membership_application_status_id = 3;
        $member->save();

        return redirect()->back()->with('success', 'Application Endorsed');
    }
    /**
     * Accept member application.
     */
    public function accept(Member $member) : RedirectResponse
    {
        $this->authorize('accept', $member);

        $member->membership_application_status_id = 4;
        $member->save();

        return redirect()->back()->with('success', 'Application Accepted');
    }

    /**
     * Display the specified resource.
     */
    public function show(Member $member) : Response
    {
        $this->authorize('view', $member);

        // Check
        $completion = [
            'part1' => [
                'status' => false,
                'title' => 'Membership Type',
            ],
            'part2' => [
                'status' => false,
                'title' => 'General',
            ],
            'part3' => [
                'status' => false,
                'title' => 'Home Address',
            ],
            'part4' => [
                'status' => false,
                'title' => 'Work Address',
            ],
            'part5' => [
                'status' => true,
                'title' => 'Other Memberships',
            ],
            'part6' => [
                'status' => false,
                'title' => 'Academic Qualifications',
            ],
            'part7' => [
                'status' => false,
                'title' => 'Work Experience',
            ],
            'part8' => [
                'status' => false,
                'title' => 'Referees',
            ],
            'part9' => [
                'status' => true,
                'title' => 'Mailing Lists',
            ],
        ];

        if ($member->membership_type_id) {
            $completion['part1']['status'] = true;
        }
        if ($member->first_name &&
            $member->last_name &&
            $member->gender_id &&
            $member->job_title &&
            $member->current_employer
        ) {
            $completion['part2']['status'] = true;
        }
        if ($member->home_address ||
            $member->home_phone ||
            $member->home_mobile ||
            $member->home_email
        ) {
            $completion['part3']['status'] = true;
        }
        if ($member->work_address ||
            $member->work_phone ||
            $member->work_mobile ||
            $member->work_email
        ) {
            $completion['part4']['status'] = true;
        }
        if ($member->qualifications()->count() &&
            $member->supportingDocuments()->where('to_delete', false)->count()) {
            $completion['part6']['status'] = true;
        }
        // @todo Part 7

        if($member->referees()->count() > 0){
            $completion['part8']['status'] = true;
        }

        if ($member->workExperiences()->count()) {
            $completion['part7']['status'] = true;
        }
        // @todo Part 8
        // Load title if exists.
        $relations = [
            "membershipType",
            "membershipApplicationStatus",
        ];
        if ($member->title_id) {
            $relations[] = "title";
        }

        return Inertia::render('Members/Show', [
            'member' => $member->load($relations),
            'options' => [
                'completion' => $completion,
            ]
        ]);
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
            'title_id' => 'nullable|int',
            'dob' => 'nullable|date',
            'gender_id' => 'required_with:membership_type_id|int|min:1',
            'job_title' => 'required_with:membership_type_id|string|max:255',
            'current_employer' => 'required_with:membership_type_id|string|max:255',
            'home_address' => 'nullable|max:255',
            'home_phone' => 'nullable|max:255',
            'home_mobile' => 'nullable|max:255',
            'home_email' => 'nullable|email|max:255',
            'work_address' => 'nullable|max:255',
            'work_phone' => 'nullable|max:255',
            'work_mobile' => 'nullable|max:255',
            'work_email' => 'nullable|email|max:255',
            'other_membership' => 'nullable|max:500',
            // 'membership_status_id' => 'int',
            'note' => 'nullable',
            'membership_application_status_id' => 'nullable|int'
        ]);

        $member->update($validated);

        return redirect()->back()->with('success', 'Member Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member)
    {
        //
    }
}
