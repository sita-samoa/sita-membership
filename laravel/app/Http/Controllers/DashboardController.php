<?php

namespace App\Http\Controllers;

use App\Enums\MembershipStatus;
use App\Models\MailingList;
use App\Models\Member;
use App\Models\MemberMailingPreference;
use App\Models\MembershipType;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Display dashboard.
     */
    public function index(): Response
    {
        // Calculate outstanding payment
        $membershipType = MembershipType::get();
        $totalOwing = 0;

        foreach ($membershipType as $m) {
            $members = Member::where('membership_status_id', MembershipStatus::LAPSED->value);
            $count = $members->where('membership_type_id', $m->id)->count();
            $totalOwing += $count * $m->annual_cost;
        }

        // Get number of mailing lists
        $mailing_list_statistics = [];
        $mailing_list = MailingList::get();
        foreach ($mailing_list as $m) {
            $list = MemberMailingPreference::where('mailing_list_id', $m->id);
            $count = $list->where('subscribed', true)->count();
            $mailing_list_statistics[$m->title] = $count;
        }

        return Inertia::render('Dashboard', [
            'totalSubmitted' => Member::where('membership_status_id', MembershipStatus::SUBMITTED->value)->count(),
            'totalLapsed' => Member::where('membership_status_id', MembershipStatus::LAPSED->value)->count(),
            'totalOwing' => $totalOwing,
            'mailingLists' => $mailing_list_statistics,
        ]);
    }
}
