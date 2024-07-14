<?php

use App\Enums\MembershipType;
use App\Models\Member;
use App\Models\Team;
use App\Models\User;
use App\Repositories\MemberInvoicesRepository;
use App\Repositories\MemberRepository;
use Database\Seeders\DatabaseSeeder;

beforeEach(function () {
    $this->seed(DatabaseSeeder::class);

    $this->user = User::factory()->create();

    // Create test member.
    $this->member = Member::factory()->create([
        'membership_type_id' => MembershipType::FULL->value,
        'user_id' => $this->user->id,
    ]);

    $rep = new MemberRepository();
    $invoice = $rep->generateInvoice($this->member);

    $rep2 = new MemberInvoicesRepository();
    $this->invoice = $rep2->addInvoice($this->member->id, $invoice);
});

test('test user can download own invoice', function () {
    $this->actingAs($this->user);

    $url = route('members.invoices.download.index', [
        'invoice' => $this->invoice->id,
        'member' => $this->member->id,
    ]);

    $response = $this->get($url);

    $response->assertStatus(200);
});

test('test can download invoice', function ($role) {
    // admin can access
    $this->actingAs($admin = User::factory()->withPersonalTeam()->create());
    $team = Team::first();

    // can access
    $user = User::factory()->create();
    $user->teams()->attach($team, ['role' => $role]);
    $this->actingAs($user->fresh());

    $url = route('members.invoices.download.index', [
        'invoice' => $this->invoice->id,
        'member' => $this->member->id,
    ]);

    $response = $this->get($url);

    $response->assertStatus(200);
})->with(['editor', 'executive', 'coordinator']);

test('test invoices are private', function () {
    $this->actingAs($user = User::factory()->create());

    $url = route('members.invoices.download.index', [
        'invoice' => $this->invoice->id,
        'member' => $this->member->id,
    ]);

    $response = $this->get($url);

    $response->assertStatus(403);
});
