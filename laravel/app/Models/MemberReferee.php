<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberReferee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'organisation',
        'phone',
        'email',
        'member_id',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
