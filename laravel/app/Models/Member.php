<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_id',
        'first_name',
        'last_name',
        'dob',
        'gender_id',
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
        'membership_status_id',
        'note',
        'membership_application_status_id',
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function title(): BelongsTo {
        return $this->belongsTo(Title::class);
    }

    public function gender(): BelongsTo {
        return $this->belongsTo(Gender::class);
    }

    public function membershipType(): BelongsTo {
        return $this->belongsTo(MembershipType::class);
    }

    public function membershipStatus(): BelongsTo {
        return $this->belongsTo(MembershipStatus::class);
    }

    public function membershipApplicationStatus(): BelongsTo {
        return $this->belongsTo(MembershipApplicationStatus::class);
    }

}
