<?php

namespace App\Services;

use App\Enums\MembershipStatus;
use App\Exports\MembersExport;
use App\Models\Member;
use App\Models\MembershipType;
use App\Models\User;
use App\Repositories\MemberRepository;
use App\Repositories\MembershipTypeRepository;
use App\Repositories\UserRepository;

// Use this class to put business logic that uses different Repository calls.
class SitaOnlineService
{
    // Consider moving this to a UserRepository class for consistency.
    public function sendUnverifiedAccountReminderForTwoDays()
    {
        $rep = new UserRepository();
        // Get unverified accounts.
        $unverifiedUsers = $rep->getUnverifiedForTwoDays();

        // Send an email to each unverified user.
        $rep->notifyOfUnverifiedAccount($unverifiedUsers);
    }

    public function sendUnverifiedAccountReminderForMoreThanTwoDays()
    {
        $rep = new UserRepository();
        // Get unverified accounts.
        $unverifiedUsers = $rep->getUnverifiedMoreThanTwoDays();

        // Send an email to each unverified user.
        $rep->notifyOfUnverifiedAccount($unverifiedUsers);
    }

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

    // Helper function for more readable code.
    public function isMemberHasPaidMembership(Member $member)
    {
        return !$this->isMemberHasFreeMembership($member);
    }

    public function getMembersExport($membership_status_id = '', $search = '')
    {
        return new MembersExport($membership_status_id, $search);
    }
}
