<?php

namespace App\Http\Controllers;

use App\Events\SubReminderRequested;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Member;
use App\Models\MemberMembershipStatus;
use App\Models\MembershipType;
use App\Models\Team;
use App\Policies\MemberPolicy;
use App\Notifications\SubReminder;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request as FacadesRequest;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) : Response
    {
        $this->authorize('viewAny', Member::class);

        return Inertia::render('Members/Index', [
            'filters' => FacadesRequest::all('membership_status_id'),
            'members' => Member::orderBy('first_name')
                ->when(FacadesRequest::input('membership_status_id'),
                    function($query) {
                        $query->where(FacadesRequest::only('membership_status_id'));
                    }
                )
                ->with('membershipType', 'title', 'membershipStatus')
                ->paginate(10)
                ->withQueryString()
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
        // set non fillable fields
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
    }

    /**
     * Submit member application.
     */
    public function submit(Member $member, Request $request) : RedirectResponse
    {
        $this->authorize('submit', $member);

        $member->membership_status_id = 2;
        $member->save();

        // add record to member membership status
        $this->recordAction($member, $request->user()->id);

        return redirect()->back()->with('success', 'Application Submitted');
    }
    /**
     * Endorse member application.
     */
    public function endorse(Member $member, Request $request) : RedirectResponse
    {
        $this->authorize('endorse', $member);

        $member->membership_status_id = 3;
        $member->save();

        // add record to member membership status
        $this->recordAction($member, $request->user()->id);

        return redirect()->back()->with('success', 'Application Endorsed');
    }
    /**
     * Accept member application.
     */
    public function accept(Member $member, Request $request) : RedirectResponse
    {
        $this->authorize('accept', $member);

        $member->membership_status_id = 4;
        $member->save();

        // calculate end of financial year (June 30)
        $month = Carbon::now()->month;
        $year = Carbon::now()->year;

        if ($month > 6) {
            $year += 1;
        }

        $to_date = Carbon::create($year, 6, 30);

        // add record to member membership status
        $this->recordAction($member, $request->user()->id, $to_date);

        return redirect()->back()->with('success', 'Application Accepted');
    }
    /**
     * Send a sub reminder to member.
     */
    public function sendSubReminder(Member $member) : RedirectResponse
    {
        $this->authorize('sendSubReminder', $member);

        // Email will be sent in a queue.
        $member->user->notify(new SubReminder($member));

        return redirect()->back()->with('success', 'Reminder scheduled.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Member $member) : Response
    {
        $this->authorize('view', $member);

        // @todo Part 8
        // Load title if exists.
        $relations = [
            "membershipType",
            "membershipStatus",
        ];
        if ($member->title_id) {
            $relations[] = "title";
        }

        return Inertia::render('Members/Show', [
            'member' => $member->load($relations),
            'options' => [
                'completion' => $member->completions,
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
            'membership_status_id' => 'nullable|int'
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

    private function recordAction(Member $member, $user_id, $to_date = null) {
        $memberMembershipStatus = new MemberMembershipStatus([
            'member_id' => $member->id,
            'membership_status_id' => $member->membership_status_id,
            'user_id' => $user_id,
            'from_date' => Carbon::now(),
        ]);
        if ($to_date) {
            $memberMembershipStatus->to_date = $to_date;
        }
        $memberMembershipStatus->save();
    }
}
