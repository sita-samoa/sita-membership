<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\MemberMailingPreference;
use Illuminate\Database\Seeder;

class MembersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $members_num = 500;

        Member::factory()
            ->count($members_num)
            ->create();
        
        // add members to mailing lists
        for($i = 0; $i < $members_num; $i++){
            MemberMailingPreference::create([
                "member_id" => $i + 1,
                "mailing_list_id" => rand(1,2),
                "subscribed" => rand(0, 1)
            ]);
        }
    }
}
