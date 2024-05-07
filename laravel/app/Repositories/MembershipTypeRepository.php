<?php

namespace App\Repositories;

use App\Models\MembershipType;

class MembershipTypeRepository extends Repository
{
    public function getByCode(string $code)
    {
        return MembershipType::where('code', $code)->firstOrFail();
    }

    public function getFreeMemberships()
    {
        return MembershipType::where('annual_cost', 0)->get();
    }
}
