<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;

class DevSetupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create dev users per role
        $team = Team::first();
        $user = User::firstOrCreate(
            ['email' => 'editor@example.com'], [
                'name' => 'Demo Editor',
                'password' => bcrypt('editor'),
            ]
        );
        $user->ownedTeams()->save(Team::forceCreate([
            'user_id' => $user->id,
            'name' => explode(' ', $user->name, 2)[0]."'s Team",
            'personal_team' => true,
        ]));
        $user->teams()->attach($team, ['role' => 'editor']);

        $user = User::firstOrCreate(
            ['email' => 'executive@example.com'], [
                'name' => 'Demo executive',
                'password' => bcrypt('executive'),
            ]
        );
        $user->ownedTeams()->save(Team::forceCreate([
            'user_id' => $user->id,
            'name' => explode(' ', $user->name, 2)[0]."'s Team",
            'personal_team' => true,
        ]));
        $user->teams()->attach($team, ['role' => 'executive']);

        $user = User::firstOrCreate(
            ['email' => 'coordinator@example.com'], [
                'name' => 'Demo coordinator',
                'password' => bcrypt('coordinator'),
            ]
        );
        $user->ownedTeams()->save(Team::forceCreate([
            'user_id' => $user->id,
            'name' => explode(' ', $user->name, 2)[0]."'s Team",
            'personal_team' => true,
        ]));
        $user->teams()->attach($team, ['role' => 'coordinator']);

        // Member::factory()
        //     ->count(500)
        //     ->create();
    }
}
