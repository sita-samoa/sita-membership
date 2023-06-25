<?php

use App\Models\Member;
use App\Models\MemberMailingPreference;
use App\Models\User;

beforeEach(function () {
    $this->seed(DatabaseSeeder::class);
});

test('test member subscribes to mailing list', function () {
    $this->actingAs($user = User::factory()->create());
    $member = Member::factory()->for($user)->create();

    $response = $this->put(
        '/members/'.$member->id.'/subscribe',
        ['mailing_list_id' => 1, 'subscribe' => true]
    );
    $response->assertStatus(302);
    expect($member->mailingLists()->first()->pivot->subscribed)->toEqual(true);
});

test('test member unsubscribes from mailing list', function () {
    $this->actingAs($user = User::factory()->create());
    $member = Member::factory()->for($user)->create();

    MemberMailingPreference::create([
        'member_id' => $member->id,
        'mailing_list_id' => 1,
        'subscribed' => true,
    ]);

    $response = $this->put(
        '/members/'.$member->id.'/subscribe',
        ['mailing_list_id' => 1, 'subscribe' => false]
    );
    $response->assertStatus(302);
    expect($member->mailingLists()->first()->pivot->subscribed)->toEqual(false);
});
