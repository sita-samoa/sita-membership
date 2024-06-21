<?php

namespace Database\Seeders;

use App\Enums\MembershipStatus;
use App\Models\Member;
use App\Models\MemberMailingPreference;
use App\Models\MemberMembershipStatus;
use App\Repositories\MemberRepository;
use Carbon\Carbon;
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

        $rep = new MemberRepository();

        // add members to mailing lists
        for ($i = 0; $i < $members_num; $i++) {
            $current = Carbon::now();
            $date = rand(1, 2) == 1 ? $current->toDateString() : $current->subMonth()->toDateString();
            MemberMailingPreference::create([
                'member_id' => $i + 1,
                'mailing_list_id' => rand(1, 2),
                'subscribed' => rand(0, 1),
                'created_at' => $current->toDateString(),
                'updated_at' => $date,
            ]);
            $from_date = Carbon::now();
            $to_date = $rep->generateEndDate($from_date);

            // Only generate a receipt_number if the status is accepted.
            $status_id = rand(1, 8);
            $receipt_number = $status_id == MembershipStatus::ACCEPTED->value ? rand(100000, 999999) : 0;

            MemberMembershipStatus::create([
                'member_id' => $i + 1,
                'membership_status_id' => $status_id,
                'user_id' => 1,
                'from_date' => $from_date,
                'to_date' => $to_date,
                'receipt_number' => $receipt_number,
            ]);

        }
    }
}
