<?php

namespace App\Http\Controllers;

use App\Enums\MembershipStatus;
use App\Models\MailingList;
use App\Models\Member;
use App\Models\MemberMailingPreference;
use App\Services\SitaOnlineService;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    protected $sitaOnlineService;

    public function __construct(SitaOnlineService $sitaOnlineService)
    {
        $this->sitaOnlineService = $sitaOnlineService;
    }

    /**
     * Display dashboard.
     */
    public function index(): Response
    {
        // Calculate outstanding payment
        $totalOwing = $this->sitaOnlineService->getOutstandingPayment();
        $totalCollected = $this->sitaOnlineService->getTotalCollected();

        // Get number of mailing lists
        $mailing_list_statistics = [];
        $mailing_list = MailingList::get();
        foreach ($mailing_list as $m) {
            $list = MemberMailingPreference::where('mailing_list_id', $m->id);
            $count = $list->where('subscribed', true)->count();
            $mailing_list_statistics[$m->id] = [$m->title, $count];
        }

        $canReadAny = Auth::user()->permissions['canReadAny'];

        return Inertia::render('Dashboard', [
            'totalSubmitted' => $canReadAny ?
                Member::where('membership_status_id', MembershipStatus::SUBMITTED->value)->count() : 0,
            'totalLapsed' => $canReadAny ?
                Member::where('membership_status_id', MembershipStatus::LAPSED->value)->count() : 0,
            'totalOwing' => $canReadAny ?
                $totalOwing : 0,
            'totalCollected' => $canReadAny ?
                $totalCollected : 0,
            'mailingLists' => $canReadAny ?
                $mailing_list_statistics : [],
            'totalEndorsed' => $canReadAny ?
                Member::where('membership_status_id', MembershipStatus::ENDORSED->value)->count() : [],
        ]);
    }
}
