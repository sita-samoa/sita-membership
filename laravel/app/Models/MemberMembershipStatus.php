<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberMembershipStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'membership_status_id',
        'user_id',
        'from_date',
        'to_date',
        'receipt_number',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function membershipStatus(): BelongsTo
    {
        return $this->belongsTo(MembershipStatus::class);
    }
}
