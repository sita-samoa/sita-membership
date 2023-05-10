<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberMembershipStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'membership_status_id',
        'user_id',
        'from_date',
        'to_date',
    ];
}
