<?php

namespace App\Http\Repositories;

use App\Models\MemberMembershipStatus;
use App\Notifications\ExpiringSubReminder;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class MemberMembershipStatusRepository extends Repository
{
  public function getByStatusIdExpiringBetween(int $status_id, Carbon $current, Carbon $date, int $limit = 10): Collection
  {
    return MemberMembershipStatus::where('membership_status_id', $status_id)
      ->whereBetween('to_date', $current->toPeriod($date))
      ->latest()
      ->limit($limit)
      ->get();
  }

  public function getByStatusIdExpiringIn3Months(int $status_id, Carbon $current = null, int $limit = -1): Collection
  {
    if ($current == null) {
      $current = Carbon::now();
    }
    return $this->getByStatusIdExpiringBetween($status_id, $current, Carbon::now()->addMonthsNoOverflow(3), $limit);
  }

  public function sendExpiringMembershipReminder(Carbon $current = null) {
    if ($current == null) {
      $current = Carbon::now();
    }

    $ids = [];
    // Get profiles that will expire in 3 months or less
    $action_status_id = 4;
    $statuses = $this->getByStatusIdExpiringIn3Months($action_status_id);
    // Make sure we dont have duplicate member ids (in case it was Activated twice)
    // @todo - ensure that member cannot be activated twice
    // @todo - load matches into a queue to be run every 5 mins
    foreach ($statuses as $status) {
      $id = $status->member->id;
      // @todo - dont know why below doesnt work
      // if (!in_array($id, $ids)) {
      //   $ids[$id] = $status;
      // }
      $ids[$id] = $status;
    }
    foreach ($ids as $status) {
      $member = $status->member;
      $user = $member->user;
      $expiry_date = $status->to_date;
      $days = $current->diffInDays($expiry_date);

      $user->notify(new ExpiringSubReminder($member, $days));

      // @todo - Add to dash board list of members that will expire in 3
      //  months or less.
      // @todo - Add a button to send bulk remindrs to those on the list
      // @todo - Perform bulk operations on the member list (e.g. send reminder)
    }
  }
}
