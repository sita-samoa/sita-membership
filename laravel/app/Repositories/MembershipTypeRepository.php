<?php

namespace App\Repositories;

use App\Models\MembershipType;

class MembershipTypeRepository extends Repository
{
    public function getByCode(string $code)
    {
        return MembershipType::where('code', $code)->firstOrFail();
    }
}
