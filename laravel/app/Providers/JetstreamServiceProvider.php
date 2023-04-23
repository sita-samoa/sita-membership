<?php

namespace App\Providers;

use App\Actions\Jetstream\AddTeamMember;
use App\Actions\Jetstream\CreateTeam;
use App\Actions\Jetstream\DeleteTeam;
use App\Actions\Jetstream\DeleteUser;
use App\Actions\Jetstream\InviteTeamMember;
use App\Actions\Jetstream\RemoveTeamMember;
use App\Actions\Jetstream\UpdateTeamName;
use Illuminate\Support\ServiceProvider;
use Laravel\Jetstream\Jetstream;

class JetstreamServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configurePermissions();

        Jetstream::createTeamsUsing(CreateTeam::class);
        Jetstream::updateTeamNamesUsing(UpdateTeamName::class);
        Jetstream::addTeamMembersUsing(AddTeamMember::class);
        Jetstream::inviteTeamMembersUsing(InviteTeamMember::class);
        Jetstream::removeTeamMembersUsing(RemoveTeamMember::class);
        Jetstream::deleteTeamsUsing(DeleteTeam::class);
        Jetstream::deleteUsersUsing(DeleteUser::class);
    }

    /**
     * Configure the roles and permissions that are available within the application.
     */
    protected function configurePermissions(): void
    {
        Jetstream::defaultApiTokenPermissions(['read']);

        Jetstream::role('admin', 'Administrator', [
            // 'create',
            // 'read',
            // 'update',
            // 'delete',
            'member:read_any',
            'member:endorse',
            'member:read_any',
            'member:update_any',
            'member:delete_any',
            'member:accept',
            'member:submit_any',
        ])->description('Administrator users can perform any action.');

        Jetstream::role('executive', 'Executive', [
            'member:read_any',
            'member:endorse',
        ])->description('SITA Executive users have the ability to read, create, and update.');

        Jetstream::role('coordinator', 'Coordinator', [
            'member:create_many',
            'member:read_any',
            'member:update_any',
            'member:delete_any',
            'member:accept',
            'member:submit_any',
        ])->description('SITA Coordinator users have the ability to read, create, and update.');
    }
}
