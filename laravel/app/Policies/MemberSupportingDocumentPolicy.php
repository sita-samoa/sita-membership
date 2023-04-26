<?php

namespace App\Policies;

use App\Models\Member;
use App\Models\MemberSupportingDocument;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MemberSupportingDocumentPolicy
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
    public function view(User $user, MemberSupportingDocument $memberSupportingDocument): bool
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
    public function update(User $user, MemberSupportingDocument $memberSupportingDocument): bool
    {
        $member = Member::where('id', $memberSupportingDocument
            ->member_id)->first();

        return $member->user()->is($user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MemberSupportingDocument $memberSupportingDocument): bool
    {
        return $this->update($user, $memberSupportingDocument);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MemberSupportingDocument $memberSupportingDocument): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MemberSupportingDocument $memberSupportingDocument): bool
    {
        //
    }
}
