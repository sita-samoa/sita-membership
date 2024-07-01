<?php

use App\Enums\MembershipStatus;
use App\Models\Member;
use App\Models\User;
use App\Services\SitaOnlineService;
use Database\Seeders\DatabaseSeeder;

beforeEach(function () {
    $this->seed(DatabaseSeeder::class);

    Member::factory()->create(['membership_status_id' => MembershipStatus::ACCEPTED->value]);
    Member::factory()->create(['membership_status_id' => MembershipStatus::SUBMITTED->value]);
    Member::factory()->create(['membership_status_id' => MembershipStatus::LAPSED->value]);
});

test('members exported', function () {
    $this->actingAs($user = User::factory()->withPersonalTeam()->create());
    $response = $this->get('/members/export');

    $response->assertStatus(200);
});

test('export member with null title_id', function () {
    $this->actingAs($user = User::factory()->withPersonalTeam()->create());
    $member = Member::factory()->create(['title_id' => null]);
    $response = $this->get('/members/export');

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    $disposition = $response->headers->get('Content-Disposition');
    $filename = substr($disposition, strpos($disposition, '=') + 1);
    expect($filename)->toStartWith('members_');
});

test('members exported has at least 1 row', function () {
    Member::factory()->create();

    $service = new SitaOnlineService();
    $sheet = $service->getMembersExport()->collection();

    expect($sheet)->toHaveCount(4);
});

test('filtered members exported has at least 1 row', function () {
    $service = new SitaOnlineService();
    $sheet = $service->getMembersExport(MembershipStatus::ACCEPTED->value)->collection();

    expect($sheet)->toHaveCount(1);
});
