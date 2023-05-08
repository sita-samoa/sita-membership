<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Add Membership Type Options
        DB::table('membership_types')->insert([
            'id' => 1,
            'code' => 'full',
            'title' => 'Full',
        ]);
        DB::table('membership_types')->insert([
            'id' => 2,
            'code' => 'associate',
            'title' => 'Associate',
        ]);
        DB::table('membership_types')->insert([
            'id' => 3,
            'code' => 'affiliate',
            'title' => 'Affiliate',
        ]);
        DB::table('membership_types')->insert([
            'id' => 4,
            'code' => 'student',
            'title' => 'Student',
        ]);
        DB::table('membership_types')->insert([
            'id' => 5,
            'code' => 'fellow',
            'title' => 'Fellow',
        ]);

        // Add Gender Options
        DB::table('genders')->insert([
            'id' => 1,
            'code' => 'male',
            'title' => 'Male',
        ]);
        DB::table('genders')->insert([
            'id' => 2,
            'code' => 'female',
            'title' => 'Female',
        ]);
        DB::table('genders')->insert([
            'id' => 3,
            'code' => 'faafafine',
            'title' => 'Faafafine',
        ]);
        DB::table('genders')->insert([
            'id' => 4,
            'code' => 'prefer-not-to-say',
            'title' => 'Prefer not to say',
        ]);

        // Add Title Options
        DB::table('titles')->insert([
            'id' => 1,
            'code' => 'mr',
            'title' => 'Mr',
        ]);
        DB::table('titles')->insert([
            'id' => 2,
            'code' => 'mrs',
            'title' => 'Mrs',
        ]);
        DB::table('titles')->insert([
            'id' => 3,
            'code' => 'ms',
            'title' => 'Ms',
        ]);
        DB::table('titles')->insert([
            'id' => 4,
            'code' => 'dr',
            'title' => 'Dr',
        ]);

        // Add Membership Statuses
        DB::table('membership_statuses')->insert([
            'id' => 1,
            'code' => 'pending',
            'title' => 'Pending',
        ]);
        DB::table('membership_statuses')->insert([
            'id' => 2,
            'code' => 'active',
            'title' => 'Active',
        ]);
        DB::table('membership_statuses')->insert([
            'id' => 3,
            'code' => 'expired',
            'title' => 'Expired',
        ]);
        DB::table('membership_statuses')->insert([
            'id' => 4,
            'code' => 'banned',
            'title' => 'Banned',
        ]);

        // Add Membership Application Statuses
        DB::table('membership_application_statuses')->insert([
            'id' => 1,
            'code' => 'draft',
            'title' => 'Draft',
        ]);
        DB::table('membership_application_statuses')->insert([
            'id' => 2,
            'code' => 'submitted',
            'title' => 'Submitted',
        ]);
        DB::table('membership_application_statuses')->insert([
            'id' => 3,
            'code' => 'endorsed',
            'title' => 'Endorsed',
        ]);
        DB::table('membership_application_statuses')->insert([
            'id' => 4,
            'code' => 'accepted',
            'title' => 'Accepted',
        ]);
    }
}
