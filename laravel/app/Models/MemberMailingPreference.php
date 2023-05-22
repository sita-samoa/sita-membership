<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MemberMailingPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id', 
        'mailing_list_id',
    ];

    public function member() : BelongsTo {
        return $this->belongsTo(Member::class);
    }

    public function mailingList() : HasOne {
        return $this->hasOne(MailingList::class);
    }
}
