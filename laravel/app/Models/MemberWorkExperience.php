<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

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
        'is_current',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
