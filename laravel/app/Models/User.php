<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'email',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
        'role',
    ];

    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::needsRehash($password) ? Hash::make($password) : $password;
    }

    public function getRoleAttribute()
    {
        if (($role = $this->teamRole(Team::first())) instanceof \Laravel\Jetstream\Role) {
            return [
                'key' => $role->key,
                'name' => $role->name,
            ];
        }

        return null;
    }

    public function getPermissionsAttribute()
    {
        $request = request();
        $member = $request->member;

        if ($member) {
            return [
                'canReadAny' => $this->can('viewAny', Member::class),
                'canRead' => $this->can('view', $member),
                'canUpdate' => $this->can('update', $member),
                'canDelete' => $this->can('delete', $member),
                'canSubmit' => $this->can('submit', $member),
                'canEndorse' => $this->can('endorse', $member),
                'canAccept' => $this->can('accept', $member),
                'canSendSubReminder' => $this->can('sendSubReminder', $member),
                'canSendPastDueSubReminder' => $this->can('sendPastDueSubReminder', $member),
            ];
        }

        return [
            'canReadAny' => $this->can('viewAny', Member::class),
            'canRead' => true,
            'canUpdate' => true,
            'canDelete' => true,
            'canSubmit' => true,
            'canEndorse' => false,
            'canAccept' => false,
            'canSendSubReminder' => false,
            'canSendPastDueSubReminder' => false,
        ];
    }
}
