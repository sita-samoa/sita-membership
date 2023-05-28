<?php

namespace App\Repositories;

use App\Models\Member;
use App\Models\MemberReferee;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class MemberRepository extends Repository
{
  const MONTH_FOR_END_OF_FINANCIAL_YEAR = 6; // June.
  const DAYS_OF_MONTH_OF_FINANCIAL_YEAR = 30;

  public function generateEndDate(Carbon $time = Carbon::now()) {
    $month = MemberRepository::MONTH_FOR_END_OF_FINANCIAL_YEAR;
    $day = MemberRepository::DAYS_OF_MONTH_OF_FINANCIAL_YEAR;

    if ($time->month() > $month) {
      $next_year = $time->year + 1;
      return new Carbon($next_year, $month, $day);
    }
    return new Carbon($time->year, $month, $day);
  }
}
