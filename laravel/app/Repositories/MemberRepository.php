<?php

namespace App\Repositories;

use App\Enums\MembershipStatus;
use App\Models\Member;
use App\Models\MemberMembershipStatus;
use App\Models\User;
use Carbon\Carbon;

class MemberRepository extends Repository
{
    final public const MONTH_FOR_END_OF_FINANCIAL_YEAR = 6; // June.

    final public const DAYS_OF_MONTH_OF_FINANCIAL_YEAR = 30;

    public function generateEndDate(Carbon $current_dt = null)
    {
        if ($current_dt == null) {
            $current_dt = Carbon::now();
        }

        // Set end of financial year (June 30)
        $month = self::MONTH_FOR_END_OF_FINANCIAL_YEAR;
        $day = self::DAYS_OF_MONTH_OF_FINANCIAL_YEAR;

        if ($current_dt->month > $month) {
            $next_year = $current_dt->year + 1;

            return Carbon::create($next_year, $month, $day);
        }

        return Carbon::create($current_dt->year, $month, $day);
    }

    public function markOptionalFlagAsViewed(Member $member, String $flag){
        if($flag == 'viewed_other_memberships'){
            $member->viewed_other_memberships = true;
            $member->save();
        }else if($flag == 'viewed_mailing_list'){
            $member->viewed_mailing_list = true;
            $member->save();
        }
    }
    public function accept(Member $member, User $user)
    {
        $member->membership_status_id = MembershipStatus::ACCEPTED->value;
        $member->save();

        $to_date = $this->generateEndDate();

        return $this->recordAction($member, $user, $to_date);
    }

    public function recordAction(Member $member, User $user, $to_date = null)
    {
        $membership_status = new MemberMembershipStatus([
            'member_id' => $member->id,
            'membership_status_id' => $member->membership_status_id,
            'user_id' => $user->id,
            'from_date' => Carbon::now(),
        ]);
        if ($to_date) {
            $membership_status->to_date = $to_date;
        }

        return $membership_status->save();
    }
}
