<?php

namespace App\Repositories;

use App\Models\Member;
use App\Models\MemberMembershipStatus;
use App\Models\User;
use Carbon\Carbon;

class MemberRepository extends Repository
{
  const MONTH_FOR_END_OF_FINANCIAL_YEAR = 6; // June.
  const DAYS_OF_MONTH_OF_FINANCIAL_YEAR = 30;

  public function generateEndDate(Carbon $current_dt = null) {
    if ($current_dt == null) {
      $current_dt = Carbon::now();
    }

    $month = MemberRepository::MONTH_FOR_END_OF_FINANCIAL_YEAR;
    $day = MemberRepository::DAYS_OF_MONTH_OF_FINANCIAL_YEAR;

    if ($current_dt->month() > $month) {
      $next_year = $current_dt->year + 1;
      return new Carbon($next_year, $month, $day);
    }
    return new Carbon($current_dt->year, $month, $day);
  }

  public function accept(Member $member, User $user) {
    $member->membership_status_id = 4;
    $member->save();

    // calculate end of financial year (June 30)
    $month = Carbon::now()->month;
    $year = Carbon::now()->year;

    if ($month > 6) {
      $year += 1;
    }

    $to_date = Carbon::create($year, 6, 30);

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
