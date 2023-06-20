<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MailingList extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
    ];

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(Member::class, 'member_mailing_preferences', 'mailing_list_id', 'member_id')->withPivot('subscribed', 'updated_at');
    }
}
