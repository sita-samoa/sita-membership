<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class MemberWorkExperience extends Model implements Auditable
{
    use HasFactory;
    use AuditableTrait;

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
