<?php

namespace App\Repositories;

use App\Enums\MembershipStatus;
use App\Models\MemberMembershipStatus;
use App\Notifications\ExpiringSubReminder;
use App\Notifications\PastDueSubReminder;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class MemberMembershipStatusRepository extends Repository
{
  public function getByMemberIdAndStatusId($member_id,$status_id, int $limit = 10): Collection {

    return MemberMembershipStatus::where('membership_status_id', $status_id)
      ->where('member_id', $member_id)
      ->latest()
      ->limit($limit)
      ->get();
  }
  public function getByStatusIdExpiringBetween(int $status_id, Carbon $from_date, Carbon $to_date, int $limit = 10): Collection
  {
    return MemberMembershipStatus::where('membership_status_id', $status_id)
      ->whereBetween('to_date', $from_date->toPeriod($to_date))
      ->latest()
      ->limit($limit)
      ->get();
  }

  public function getExpiringIn3Months(Carbon $current = null, int $limit = -1): Collection
  {
    if ($current == null) {
      $current = Carbon::now();
    }
    $future_3_months = $current->toImmutable()->addMonthsNoOverflow(3)->toMutable();
    return $this->getByStatusIdExpiringBetween(MembershipStatus::ACCEPTED->value, $current, $future_3_months, $limit);
  }

  public function getExpiredLessThan6Months(Carbon $current = null, int $limit = -1): Collection
  {
    if ($current == null) {
      $current = Carbon::now();
    }
    $past_6_months = $current->toImmutable()->subMonthsNoOverflow(6)->toMutable();
    return $this->getByStatusIdExpiringBetween(MembershipStatus::ACCEPTED->value, $past_6_months, $current, $limit);
  }

  /**
   * Send Past Due Sub Reminders to Members whose subs expired 6 months ago or less
   *
   * @param Carbon|null $current
   * @return void
   */
  public function sendPastDueSubReminders(Carbon $current = null)
  {
    if ($current == null) {
      $current = Carbon::now();
    }

    $ids = [];
    // Get profiles that will expire in 3 months or less
    $lapsed_status = MembershipStatus::LAPSED->value;
    $statuses = $this->getExpiredLessThan6Months($current);
    // @todo - load matches into a queue to be run every 5 mins
    foreach ($statuses as $status) {
      // ensure member status is correct
      if ($status->member->membershipStatus->id === $lapsed_status) {
        $id = $status->member->id;

        // Make sure we dont have duplicate member ids (in case it was Activated twice)
        if (!in_array($id, array_keys($ids))) {
          $ids[$id] = $status;
        }
      }
    }

    foreach ($ids as $status) {
      $member = $status->member;
      $user = $member->user;
      $expiry_date = $status->to_date;
      // $days = $current->diffInDays($expiry_date);
      $end_grace_period = Carbon::createFromFormat('Y-m-d', $expiry_date)->addMonthsNoOverflow(6);

      $user->notify(new PastDueSubReminder($member, $end_grace_period));

      // @todo - Add to dash board list of members that will expire in 3
      //  months or less.
      // @todo - Add a button to send bulk remindrs to those on the list
      // @todo - Perform bulk operations on the member list (e.g. send reminder)
    }
  }

  /**
   * Send Expiring Membership Reminders to members whose subs will expire
   * in 3 months or less
   *
   * @param Carbon|null $current
   * @return void
   */
  public function sendExpiringMembershipReminders(Carbon $current = null)
  {
    if ($current == null) {
      $current = Carbon::now();
    }

    $ids = [];
    // Get profiles that will expire in 3 months or less
    $accepted_status = MembershipStatus::ACCEPTED->value;
    $statuses = $this->getExpiringIn3Months($current);
    // @todo - load matches into a queue to be run every 5 mins
    foreach ($statuses as $status) {
      // ensure member status is correct
      if ($status->member->membershipStatus->id === $accepted_status) {
        $id = $status->member->id;

        // Make sure we dont have duplicate member ids (in case it was Activated twice)
        if (!in_array($id, array_keys($ids))) {
          $ids[$id] = $status;
        }
      }
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
