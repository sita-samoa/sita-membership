<?php

use App\Models\Member;
use App\Models\MemberSupportingDocument;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Storage;
use Laravel\Jetstream\Features;

beforeEach(function () {
    $this->seed(DatabaseSeeder::class);
});

test('user accounts can be deleted', function () {
    Storage::fake();

    $this->actingAs($user = User::factory()->create());
    $members = Member::factory()->count(2)->for($user)->create();
    $memberAttributes = $members->map->getAttributes();
    $filePath = 'supportingDocuments/qualification.pdf';
    Storage::put($filePath, 'Qualification document');
    $document = new MemberSupportingDocument();
    $document->file_name = 'qualification.pdf';
    $document->file_path = $filePath;
    $document->file_size = Storage::size($filePath);
    $members->first()->supportingDocuments()->save($document);
    $documentAttributes = $document->getAttributes();

    $response = $this->delete('/user', [
        'password' => 'password',
    ]);

    $response->assertRedirect('/');
    $this->assertSoftDeleted($user);
    $this->assertGuest('web');

    foreach ($memberAttributes as $attributes) {
        $this->assertDatabaseHas('members', $attributes);
    }

    $this->assertDatabaseHas(
        'member_supporting_documents',
        $documentAttributes
    );
    Storage::assertExists($filePath);
})->skip(function () {
    return ! Features::hasAccountDeletionFeatures();
}, 'Account deletion is not enabled.');

test('correct password must be provided before account '
    .'can be deleted', function () {
        $this->actingAs($user = User::factory()->create());
        $member = Member::factory()->for($user)->create();
        $memberAttributes = $member->getAttributes();

        $response = $this->delete('/user', [
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors(['password']);
        $this->assertAuthenticatedAs($user, 'web');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'deleted_at' => null,
        ]);
        $this->assertDatabaseHas('members', $memberAttributes);
    })->skip(function () {
        return ! Features::hasAccountDeletionFeatures();
    }, 'Account deletion is not enabled.');
