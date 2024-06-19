<?php

namespace App\Repositories;

use App\Models\MembershipStatus;

class MembershipStatusesRepository extends Repository
{
    public function get()
    {
        return MembershipStatus::limit(100)->get();
    }
}
