<?php

namespace App\Repositories;

use App\Models\MemberWorkExperience;

class MemberWorkExperienceRepository extends Repository
{
    /**
     * Check if member has current work experience.
     *
     * @param  Member  $member
     */
    public function hasCurrentWorkExperience(int $member_id): bool
    {
        if ($this->countCurrentWorkExperience($member_id) > 0) {
            return true;
        }

        return false;
    }

    /**
     * Count the current work experience of a member.
     *
     * @param  datatype  $member_id description
     */
    public function countCurrentWorkExperience(int $member_id, int $exclude_id = 0): int
    {
        if ($exclude_id) {
            return MemberWorkExperience::where('member_id', $member_id)
                ->where('id', '!=', $exclude_id)
                ->where('is_current', true)
                ->count();
        }

        return MemberWorkExperience::where('member_id', $member_id)
            ->where('is_current', true)
            ->count();
    }
}
