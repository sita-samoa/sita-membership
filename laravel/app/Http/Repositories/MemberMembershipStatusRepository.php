<?php

namespace App\Http\Repositories;

use App\Models\MemberMembershipStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class MemberMembershipStatusRepository extends Repository
{
  public function getByStatusIdExpiringIn(int $status_id, Carbon $current, Carbon $date, int $limit = 10): Collection
  {
    return MemberMembershipStatus::where('membership_status_id', $status_id)
      ->whereBetween('to_date', $current->toPeriod($date))
      ->latest()
      ->limit($limit)
      ->get();
  }
}
