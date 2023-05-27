<?php

namespace App\Http\Repositories;

use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class MemberRepository extends Repository
{
  const ACTIVE_STATUS_ID = 4;

  public function getMembersExpiringIn(Carbon $since, int $limit = 10): Collection
  {
    // Get all Activate Members
    // @todo - how to ignore limit pass -1
    $list = $this->getActiveMembers();

    // Check how long until they expire
    foreach ($list as $member) {

    }

    // Remove below when ready
    return Member::where('overdue_since', '>=', $since)
      ->limit($limit)
      ->orderBy('overdue_since')
      ->get();
  }

  protected function getMembersByStatus(int $statusId, $limit = 10): Collection
  {
    return Member::where('member_status_id', $statusId)
      ->limit($limit)
      ->get();
  }

  public function getActiveMembers($limit = 10) {
    return $this->getMembersByStatus(MemberRepository::ACTIVE_STATUS_ID, $limit);
  }
}
