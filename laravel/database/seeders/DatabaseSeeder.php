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
            'annual_cost' => 100,
        ]);
        DB::table('membership_types')->insert([
            'id' => 2,
            'code' => 'associate',
            'title' => 'Associate',
            'annual_cost' => 50,
        ]);
        DB::table('membership_types')->insert([
            'id' => 3,
            'code' => 'affiliate',
            'title' => 'Affiliate',
            'annual_cost' => 50,
        ]);
        DB::table('membership_types')->insert([
            'id' => 4,
            'code' => 'student',
            'title' => 'Student',
            'annual_cost' => 0,
        ]);
        DB::table('membership_types')->insert([
            'id' => 5,
            'code' => 'fellow',
            'title' => 'Fellow',
            'annual_cost' => 0,
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
            'code' => 'draft',
            'title' => 'Draft',
        ]);
        DB::table('membership_statuses')->insert([
            'id' => 2,
            'code' => 'submitted',
            'title' => 'Submitted',
        ]);
        DB::table('membership_statuses')->insert([
            'id' => 3,
            'code' => 'endorsed',
            'title' => 'Endorsed',
        ]);
        DB::table('membership_statuses')->insert([
            'id' => 4,
            'code' => 'accepted',
            'title' => 'Accepted',
        ]);
        DB::table('membership_statuses')->insert([
            'id' => 5,
            'code' => 'lapsed',
            'title' => 'Lapsed',
        ]);
        DB::table('membership_statuses')->insert([
            'id' => 6,
            'code' => 'expired',
            'title' => 'Expired',
        ]);
        DB::table('membership_statuses')->insert([
            'id' => 7,
            'code' => 'banned',
            'title' => 'Banned',
        ]);

        // add mailing list options
        DB::table('mailing_lists')->insert([
            'id' => 1,
            'code' => 'general',
            'title' => 'SITA General - General Announcements for the public.',
        ]);
        DB::table('mailing_lists')->insert([
            'id' => 2,
            'code' => 'members',
            'title' => 'SITA Members - Announcements for SITA Members only.',
        ]);

        // Add Invoice Statuses
        if (DB::table('invoice_statuses')->count() == 0) {
            DB::table('invoice_statuses')->insert([
                [
                    'id' => 1,
                    'code' => 'due',
                    'title' => 'Due',
                ],
                [
                    'id' => 2,
                    'code' => 'paid',
                    'title' => 'Paid',
                ],
                [
                    'id' => 3,
                    'code' => 'cancelled',
                    'title' => 'Cancelled',
                ],
            ]);
        }
    }
}
