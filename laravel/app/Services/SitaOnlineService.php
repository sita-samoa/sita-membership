<?php

namespace App\Services;

use App\Enums\MembershipStatus;
use App\Models\Member;
use App\Models\MembershipType;

class SitaOnlineService
{
    public function calculateOutstandingPayment()
    {
        $membershipType = MembershipType::get();
        $totalOwing = 0;

        foreach ($membershipType as $m) {
            $members = Member::where('membership_status_id', MembershipStatus::LAPSED->value);
            $count = $members->where('membership_type_id', $m->id)->count();
            $totalOwing += $count * $m->annual_cost;
        }

        return $totalOwing;
    }

    public function calculateTotalCollected()
    {
        $membershipType = MembershipType::get();
        $totalFunds = 0;

        foreach ($membershipType as $m) {
            $members = Member::where('membership_status_id', MembershipStatus::ACCEPTED->value);
            $count = $members->where('membership_type_id', $m->id)->count();
            $totalFunds += $count * $m->annual_cost;
        }

        return $totalFunds;
    }
}
