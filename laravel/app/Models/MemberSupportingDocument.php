<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberSupportingDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
    ];

    public function member(): BelongsTo {
        return $this->belongsTo(Member::class);
    }
}
