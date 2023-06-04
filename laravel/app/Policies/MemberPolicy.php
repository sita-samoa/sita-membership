<?php

namespace App\Policies;

use App\Models\Member;
use App\Models\Team;
use App\Models\User;
use App\Enums\MembershipStatus;

class MemberPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        $team = Team::first();
        return $user->hasTeamPermission($team, 'member:read_any');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Member $member): bool
    {
        $team = Team::first();
        return $member->user()->is($user) || $user->hasTeamPermission($team, 'member:read_any');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function submit(User $user, Member $member): bool
    {
        $team = Team::first();

        if ($member->completions['overall']['status']) {
            return $member->user()->is($user) || $user->hasTeamPermission($team, 'member:submit_any');
        }

        return $user->hasTeamPermission($team, 'member:submit_incomplete');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function endorse(User $user, Member $member): bool
    {
        $team = Team::first();
        return $user->hasTeamPermission($team, 'member:endorse');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function accept(User $user, Member $member): bool
    {
        $team = Team::first();

        return $user->hasTeamPermission($team, 'member:accept') &&
            (
                $member->membership_status_id == MembershipStatus::ENDORSED->value ||
                $member->membership_status_id == MembershipStatus::LAPSED->value ||
                $member->membership_status_id == MembershipStatus::EXPIRED->value
            );
    }

    /**
     * Determine whether the user can send reminder.
     *
     * Must have the permission and status must be Accepted
     */
    public function sendSubReminder(User $user, Member $member): bool
    {
        $team = Team::first();
        return $user->hasTeamPermission($team, 'member:send_sub_reminder') &&
         $member->membership_status_id == MembershipStatus::ACCEPTED->value;
    }

    /**
     * Determine whether the user can send reminder.
     *
     * Must have the permission and status must be Lapsed
     */
    public function sendPastDueSubReminder(User $user, Member $member): bool
    {
        $team = Team::first();
        return $user->hasTeamPermission($team, 'member:send_sub_reminder') &&
         $member->membership_status_id == MembershipStatus::LAPSED->value;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Member $member): bool
    {
        $team = Team::first();
        return $member->user()->is($user) || $user->hasTeamPermission($team, 'member:update_any');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Member $member): bool
    {
        $team = Team::first();
        return $member->user()->is($user) || $user->hasTeamPermission($team, 'member:delete_any');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Member $member): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Member $member): bool
    {
        //
    }
}
