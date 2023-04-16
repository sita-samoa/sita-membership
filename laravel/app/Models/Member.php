<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'title',
        // 'dob',
        'membership_type_id',
        'job_title',
        'current_employer',
        'home_address',
        'home_phone',
        'home_mobile',
        'home_email',
        'work_address',
        'work_phone',
        'work_mobile',
        'work_email',
        'other_membership',
        'note',
        'membership_type_id',
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}
