<?php

namespace App\Policies;

use App\Models\Member;
use App\Models\MemberQualification;
use App\Models\User;

class MemberQualificationPolicy
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
    public function view(User $user, MemberQualification $memberQualification): bool
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
    public function update(User $user, MemberQualification $memberQualification): bool
    {
        $member = Member::where('id', $memberQualification
            ->member_id)->first();

        return $member->user()->is($user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MemberQualification $memberQualification): bool
    {
        return $this->update($user, $memberQualification);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MemberQualification $memberQualification): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MemberQualification $memberQualification): bool
    {
        //
    }
}
