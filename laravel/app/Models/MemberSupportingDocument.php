<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class MemberSupportingDocument extends Model implements Auditable
{
    use AuditableTrait;
    use HasFactory;

    protected $fillable = [
        'title',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
