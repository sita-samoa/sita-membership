<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberWorkExperience extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'organisation',
        'position',
        'responsibilities',
        'from_date',
        'to_date',
    ];
}
