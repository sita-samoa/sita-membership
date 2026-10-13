<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (config('admin.admin_name')) {
            $user = User::firstOrCreate(
                ['email' => config('admin.admin_email')],
                [
                    'name' => config('admin.admin_name'),
                    'password' => bcrypt(config('admin.admin_password')),
                    'email_verified_at' => now(),
                ]
            );

            $user->ownedTeams()->firstOrCreate(['personal_team' => true], [
                'name' => config('admin.admin_team'),
                'personal_team' => true,
            ]);
        }
    }
}
