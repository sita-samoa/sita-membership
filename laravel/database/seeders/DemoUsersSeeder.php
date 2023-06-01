<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create dev users per role
        $team = Team::first();

        $user = User::firstOrCreate(
            ['email' => 'demo@example.com', 'email_verified_at' => now()],
            [
                'name' => 'Demo user',
                'password' => bcrypt('password'),
            ]
        );
        $user->ownedTeams()->save(Team::forceCreate([
            'user_id' => $user->id,
            'name' => explode(' ', $user->name, 2)[0]."'s Team",
            'personal_team' => true,
        ]));

        $user = User::firstOrCreate(
            ['email' => 'editor@example.com', 'email_verified_at' => now()],
            [
                'name' => 'Demo Editor',
                'password' => bcrypt('password'),
            ]
        );
        $user->ownedTeams()->save(Team::forceCreate([
            'user_id' => $user->id,
            'name' => explode(' ', $user->name, 2)[0]."'s Team",
            'personal_team' => true,
        ]));
        $user->teams()->attach($team, ['role' => 'editor']);

        $user = User::firstOrCreate(
            ['email' => 'executive@example.com', 'email_verified_at' => now()],
            [
                'name' => 'Demo executive',
                'password' => bcrypt('password'),
            ]
        );
        $user->ownedTeams()->save(Team::forceCreate([
            'user_id' => $user->id,
            'name' => explode(' ', $user->name, 2)[0]."'s Team",
            'personal_team' => true,
        ]));
        $user->teams()->attach($team, ['role' => 'executive']);

        $user = User::firstOrCreate(
            ['email' => 'coordinator@example.com', 'email_verified_at' => now()],
            [
                'name' => 'Demo coordinator',
                'password' => bcrypt('password'),
            ]
        );
        $user->ownedTeams()->save(Team::forceCreate([
            'user_id' => $user->id,
            'name' => explode(' ', $user->name, 2)[0]."'s Team",
            'personal_team' => true,
        ]));
        $user->teams()->attach($team, ['role' => 'coordinator']);
    }
}
