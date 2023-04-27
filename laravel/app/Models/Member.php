<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_id',
        'first_name',
        'last_name',
        'dob',
        'gender_id',
        'membership_type_id',
        'job_title',
        'current_employer',
        'home_address',
        'home_phone',
        'home_mobile',
        'home_email',
        'work_address',
        'work_phone',
        'work_mobile',
        'work_email',
        'other_membership',
        // 'membership_status_id',
        'note',
        // 'membership_application_status_id',
    ];

    /**
     * Set the user's title.
     *
     * @param  int  $value
     * @return void
     */
    public function setTitleIdAttribute($value)
    {
        $this->attributes['title_id'] = $value < 1 ? null : $value;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function title(): BelongsTo
    {
        return $this->belongsTo(Title::class);
    }

    public function gender(): BelongsTo
    {
        return $this->belongsTo(Gender::class);
    }

    public function membershipType(): BelongsTo
    {
        return $this->belongsTo(MembershipType::class);
    }

    public function membershipStatus(): BelongsTo
    {
        return $this->belongsTo(MembershipStatus::class);
    }

    public function membershipApplicationStatus(): BelongsTo
    {
        return $this->belongsTo(MembershipApplicationStatus::class);
    }

    public function qualifications(): HasMany
    {
        return $this->hasMany(MemberQualification::class);
    }

    public function referees(): HasMany
    {
        return $this->hasMany(MemberReferee::class);
    }

    public function workExperiences(): HasMany
    {
        return $this->hasMany(MemberWorkExperience::class);
    }

    public function supportingDocuments(): HasMany
    {
        return $this->hasMany(MemberSupportingDocument::class);
    }
}
