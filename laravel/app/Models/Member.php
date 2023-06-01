<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class Member extends Model implements Auditable
{
    use HasFactory;
    use AuditableTrait;

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

    /**
     * Get member completions.
     *
     * @return array
     */
    public function getCompletionsAttribute()
    {
        $completion = [
            'data' => [
                'part1' => [
                    'status' => false,
                    'title' => 'Membership Type',
                ],
                'part2' => [
                    'status' => false,
                    'title' => 'General',
                ],
                'part3' => [
                    'status' => false,
                    'title' => 'Home Address',
                ],
                'part4' => [
                    'status' => false,
                    'title' => 'Work Address',
                ],
                'part5' => [
                    'status' => true,
                    'title' => 'Other Memberships',
                ],
                'part6' => [
                    'status' => false,
                    'title' => 'Academic Qualifications',
                ],
                'part7' => [
                    'status' => false,
                    'title' => 'Work Experience',
                ],
                'part8' => [
                    'status' => false,
                    'title' => 'Referees',
                ],
                'part9' => [
                    'status' => true,
                    'title' => 'Mailing Lists',
                ],
            ],
            'overall' => [
                'status' => false,
                'title' => 'Overall',
            ],
        ];

        if ($this->membership_type_id) {
            $completion['data']['part1']['status'] = true;
        }
        if (
            $this->first_name &&
            $this->last_name &&
            $this->gender_id &&
            $this->job_title &&
            $this->current_employer
        ) {
            $completion['data']['part2']['status'] = true;
        }
        if (
            $this->home_address ||
            $this->home_phone ||
            $this->home_mobile ||
            $this->home_email
        ) {
            $completion['data']['part3']['status'] = true;
        }
        if (
            $this->work_address ||
            $this->work_phone ||
            $this->work_mobile ||
            $this->work_email
        ) {
            $completion['data']['part4']['status'] = true;
        }
        if (
            $this->qualifications()->count() &&
            $this->supportingDocuments()->where('to_delete', false)->count()
        ) {
            $completion['data']['part6']['status'] = true;
        }

        if ($this->workExperiences()->count()) {
            $completion['data']['part7']['status'] = true;
        }

        if ($this->referees()->count() > 0) {
            $completion['data']['part8']['status'] = true;
        }

        $overall = true;
        foreach ($completion['data'] as $value) {
            if (! $value['status']) {
                $overall = false;
                break;
            }
        }

        $completion['overall']['status'] = $overall;

        return $completion;
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

    public function mailingLists(): HasMany
    {
        return $this->hasMany(MemberMailingPreference::class);
    }

    public function membershipStatuses(): HasMany
    {
        return $this->hasMany(MemberMembershipStatus::class);
    }
}
