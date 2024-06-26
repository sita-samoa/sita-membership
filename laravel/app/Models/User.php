<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
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
        'name', 'email', 'password',
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
                'canReject' => $this->can('reject', $member),
                'canSendSubReminder' => $this->can('sendSubReminder', $member),
                'canSendPastDueSubReminder' => $this->can('sendPastDueSubReminder', $member),
                'canManageUsers' => $this->can('viewAny', self::class),
                'canUpdateMembershipStatus' => $this->can('updateMembershipStatus', $member),
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
            'canReject' => false,
            'canSendSubReminder' => false,
            'canSendPastDueSubReminder' => false,
            'canManageUsers' => $this->can('viewAny', self::class),
            'canUpdateMembershipStatus' => false,
        ];
    }

    public function isSuperUser(): bool
    {
        return $this->id === 1;
    }

    public function isDemoUser(): bool
    {
        $demo_users = [
            'demo@example.com',
            'executive@example.com',
            'coordinator@example.com',
            'editor@example.com',
        ];

        return in_array($this->email, $demo_users);
    }
}
