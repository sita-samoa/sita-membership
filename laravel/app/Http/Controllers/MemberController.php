<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Member;
use App\Models\MembershipType;
use App\Models\Team;
use App\Models\MailingList;
use App\Models\MemberMailingPreference;
use App\Notifications\PastDueSubReminder;
use App\Notifications\AcceptanceNotification;
use App\Notifications\EndorsementNotification;
use App\Notifications\SubReminder;
use App\Repositories\MemberRepository;
use App\Enums\MembershipStatus;
use App\Repositories\MemberMembershipStatusRepository;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;

class MemberController extends Controller
{
    public function __construct(public MemberRepository $rep = new MemberRepository())
    {

    }

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
        $member->membership_status_id = MembershipStatus::DRAFT->value;
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
    public function store(Request $request)
    {
        // unused
    }

    /**
     * Submit member application.
     */
    public function submit(Member $member, Request $request) : RedirectResponse
    {
        $this->authorize('submit', $member);

        $member->membership_status_id = MembershipStatus::SUBMITTED->value;
        $member->save();

        // add record to member membership status
        $this->rep->recordAction($member, $request->user());
        // Send endorsement notifications.
        $team = Team::first();
        $users = $team->allUsers();
        foreach ($users as $user) {
            if ($team->userHasPermission($user, 'member:endorse')) {
                $user->notify(new EndorsementNotification($member));
            }
        }

        return redirect()->back()->with('success', 'Application Submitted');
    }
    /**
     * Mark Viewed Other Memberships or Mailing List as true.
     */
    public function markOptionalFlagAsViewed(Member $member, Request $request) : RedirectResponse
    {
        $validated = $request->validate([
            'flag_name' => 'required|string',
        ]);

        $flag_name = $validated['flag_name'];
        $this->rep->markOptionalFlagAsViewed($member, $flag_name);

        return redirect()->back();
    }
    /**
     * Endorse member application.
     */
    public function endorse(Member $member, Request $request) : RedirectResponse
    {
        $this->authorize('endorse', $member);

        $member->membership_status_id = MembershipStatus::ENDORSED->value;
        $member->save();

        // add record to member membership status
        $this->rep->recordAction($member, $request->user());
        // Send acceptance notifications.
        $team = Team::first();
        $users = $team->allUsers();
        foreach ($users as $user) {
            if ($team->userHasPermission($user, 'member:accept')) {
                $user->notify(new AcceptanceNotification($member));
            }
        }

        return redirect()->back()->with('success', 'Application Endorsed');
    }
    /**
     * Accept member application.
     */
    public function accept(Member $member, Request $request) : RedirectResponse
    {
        $this->authorize('accept', $member);

        $this->rep->accept($member, $request->user());

        return redirect()->back()->with('success', 'Application Accepted');
    }

    /**
     * Mark a lapsed member as active
     */
    public function markActive(Member $member, Request $request) : RedirectResponse
    {
        $this->authorize('markActive', $member);

        $this->rep->accept($member, $request->user());

        return redirect()->back()->with('success', 'Member marked as active.');
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
     * Send a sub reminder to member.
     */
    public function sendPastDueSubReminder(Member $member) : RedirectResponse
    {
        $this->authorize('sendPastDueSubReminder', $member);

        $end_grace_period = Carbon::now();
        $rep = new MemberMembershipStatusRepository();
        $statuses = $rep->getByMemberIdAndStatusId($member->id, MembershipStatus::ACCEPTED->value);
        if ($statuses->count()) {
            $end_grace_period = Carbon::createFromFormat('Y-m-d', $statuses[0]->to_date);
        }

        // Email will be sent in a queue.
        $member->user->notify(new PastDueSubReminder($member, $end_grace_period->addMonthsWithoutOverflow(6)));

        return redirect()->back()->with('success', 'Reminder scheduled.');
    }

    /**
     * Toggle Member Subscription to Mailing List
     */
    public function toggleMailingListSubscription(Member $member, Request $request){
        $this->authorize('view', $member);

        $validated = $request->validate([
            'mailing_list_id' => 'required|int|min:1',
            'subscribe' => 'required|bool'
        ]);

        $mailing_list = MailingList::find($validated['mailing_list_id']);
        $foundPref = MemberMailingPreference::where('member_id', $member->id)->where('mailing_list_id', $mailing_list->id)->first();
        $subscribe = $validated['subscribe'];
        if($foundPref){
            $foundPref->subscribed = $subscribe;
            $foundPref->update();
        }else{
            $member_mailing_preference = new MemberMailingPreference();
            $member_mailing_preference->mailing_list_id = $mailing_list->id;
            $member_mailing_preference->member_id = $member->id;
            $member_mailing_preference->subscribed = $subscribe;
            $member_mailing_preference->save();
        }


        return redirect()->back()
            ->with('success', ($subscribe ? 'Subscribed to ' : 'Unsubscribed from ') . $mailing_list->title);
    }

    /**
     * Display the specified resource.
     */
    public function show(Member $member) : Response
    {
        $this->authorize('view', $member);

        $relations = [
            "membershipType",
            "membershipStatus",
        ];
        // Load title if exists.
        if ($member->title_id) {
            $relations[] = "title";
        }

        return Inertia::render('Members/Show', [
            'member' => $member->load($relations),
            'options' => [
                'completion' => $member->completions,
            ],
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
}
