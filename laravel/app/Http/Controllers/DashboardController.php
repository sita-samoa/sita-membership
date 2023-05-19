<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Display dashboard.
     */
    public function index() : Response
    {
        return Inertia::render('Dashboard', [
            'totalSubmitted' => count(Member::where('membership_status_id', 2)->get()),
            'totalLapsed' =>  count(Member::where('membership_status_id', 5)->get()),
        ]);
    }
}
