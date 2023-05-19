<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MembershipType;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Display dashboard.
     */
    public function index() : Response
    {
        // Calculate outstanding payment
        $membershipType = MembershipType::get();
        $totalOwing = 0;

        foreach ($membershipType as $m) {
            $members = Member::where('membership_status_id', 5);
            $count = $members->where('membership_type_id', $m->id)->count();
            $totalOwing += $count * $m->annual_cost;
        }

        return Inertia::render('Dashboard', [
            'totalSubmitted' => Member::where('membership_status_id', 2)->count(),
            'totalLapsed' => Member::where('membership_status_id', 5)->count(),
            'totalOwing' => $totalOwing,
        ]);
    }
}
