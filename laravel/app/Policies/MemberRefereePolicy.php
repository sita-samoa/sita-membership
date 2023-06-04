<?php

namespace App\Policies;

use App\Models\Member;
use App\Models\MemberReferee;
use App\Models\User;

class MemberRefereePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MemberReferee $memberReferee): bool
    {
        //
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
    public function update(User $user, MemberReferee $memberReferee): bool
    {
        $member = Member::where('id', $memberReferee
            ->member_id)->first();

        return $member->user()->is($user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MemberReferee $memberReferee): bool
    {
        $member = Member::where('id', $memberReferee
            ->member_id)->first();

        return $member->user()->is($user);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MemberReferee $memberReferee): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MemberReferee $memberReferee): bool
    {
        //
    }
}
