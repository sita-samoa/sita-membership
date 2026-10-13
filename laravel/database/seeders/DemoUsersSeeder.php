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
            ['email' => 'demo@example.com'],
            [
                'name' => 'Demo user',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        $user->ownedTeams()->firstOrCreate(['personal_team' => true], [
            'name' => explode(' ', $user->name, 2)[0]."'s Team",
            'personal_team' => true,
        ]);

        $user = User::firstOrCreate(
            ['email' => 'editor@example.com'],
            [
                'name' => 'Demo Editor',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        $user->ownedTeams()->firstOrCreate(['personal_team' => true], [
            'name' => explode(' ', $user->name, 2)[0]."'s Team",
            'personal_team' => true,
        ]);
        $user->teams()->syncWithoutDetaching([$team->id => ['role' => 'editor']]);

        $user = User::firstOrCreate(
            ['email' => 'executive@example.com'],
            [
                'name' => 'Demo executive',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        $user->ownedTeams()->firstOrCreate(['personal_team' => true], [
            'name' => explode(' ', $user->name, 2)[0]."'s Team",
            'personal_team' => true,
        ]);
        $user->teams()->syncWithoutDetaching([$team->id => ['role' => 'executive']]);

        $user = User::firstOrCreate(
            ['email' => 'coordinator@example.com'],
            [
                'name' => 'Demo coordinator',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        $user->ownedTeams()->firstOrCreate(['personal_team' => true], [
            'name' => explode(' ', $user->name, 2)[0]."'s Team",
            'personal_team' => true,
        ]);
        $user->teams()->syncWithoutDetaching([$team->id => ['role' => 'coordinator']]);
    }
}
