<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailingList extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
    ];

    public function member(): BelongsTo {
        return $this->belongsTo(Member::class);
    }
}
