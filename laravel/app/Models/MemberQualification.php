<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberQualification extends Model
{
    use HasFactory;

    protected $fillable = [
        'qualification',
        'year_attained',
        'institution',
        'country_id',
        'memher_id'
    ];

    public function member(): BelongsTo {
        return $this->belongsTo(Member::class);
    }

}
