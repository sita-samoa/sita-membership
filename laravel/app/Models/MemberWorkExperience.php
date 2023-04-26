<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function member(): BelongsTo {
        return $this->belongsTo(Member::class);
    }
}
