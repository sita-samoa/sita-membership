<?php

use App\Models\Member;
use App\Models\Team;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\DemoUsersSeeder;
use Database\Seeders\MembersTableSeeder;
use Database\Seeders\UsersTableSeeder;

it('repeats development setup without duplicates', function () {
    config([
        'admin.admin_name' => 'Admin',
        'admin.admin_email' => 'admin@example.com',
    ]);

    for ($run = 0; $run < 2; $run++) {
        $this->seed([
            UsersTableSeeder::class,
            DatabaseSeeder::class,
            DemoUsersSeeder::class,
        ]);
    }

    expect(User::count())->toBe(5)
        ->and(Team::count())->toBe(5);

    $this->assertDatabaseCount('team_user', 3);
    $this->assertDatabaseCount('membership_types', 5);
});

it('links demo records to newly created members on every run', function () {
    $this->seed(DatabaseSeeder::class);
    $existing = Member::factory()->create();

    $this->seed(MembersTableSeeder::class);
    $this->seed(MembersTableSeeder::class);

    expect($existing->mailingLists()->count())->toBe(0);
    $this->assertDatabaseCount('member_mailing_preferences', 1000);
    $this->assertDatabaseCount('member_membership_statuses', 1000);
    expect(Member::whereHas('mailingLists')->count())->toBe(1000);
});
