<?php

namespace App\Services;

use App\Enums\MembershipStatus;
use App\Models\Member;
use App\Models\MembershipType;
use App\Repositories\MemberRepository;
use App\Repositories\MembershipTypeRepository;

class SitaOnlineService
{
    public function getOutstandingPayment()
    {
        return $this->getTotalFundsByMembershipType(MembershipStatus::LAPSED);
    }

    public function getTotalCollected()
    {
        return $this->getTotalFundsByMembershipType(MembershipStatus::ACCEPTED);
    }

    public function getTotalFundsByMembershipType(MembershipStatus $status)
    {
        $totalFunds = 0;
        $membershipType = MembershipType::get();
        $membership_status_id = $status->value;
        $rep = new MemberRepository();

        foreach ($membershipType as $m) {
            $members = $rep->getByMembershipStatusId($membership_status_id, 0);
            $count = $members->where('membership_type_id', $m->id)->count();
            $totalFunds += $count * $m->annual_cost;
        }

        return $totalFunds;
    }

    public function isMemberHasFreeMembership(Member $member)
    {
        $mtRep = new MembershipTypeRepository();
        $memberships = $mtRep->getFreeMemberships();
        $isFreeMembership = false;

        foreach ($memberships as $freeMembership) {
            if ($member->membershipType->id == $freeMembership->id) {
                $isFreeMembership = true;
                break;
            }
        }

        return $isFreeMembership;
    }
}
