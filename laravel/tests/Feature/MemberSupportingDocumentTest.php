<?php

use App\Models\Member;
use App\Models\MemberSupportingDocument;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->seed(DatabaseSeeder::class);
});

test('test supporting document uploaded and downloaded', function () {
    $this->actingAs($user = User::factory()->create());

    $member = Member::factory()->for($user)->create();

    $response = $this->post('/members/' . $member->id . '/documents', [
        'file' => $file = UploadedFile::fake()->image('random.jpg')
    ]);

    $response->assertStatus(302);

    $supportingDocument = MemberSupportingDocument::latest()->first();

    expect('supportingDocuments/' . $file->hashName())->toEqual($supportingDocument->file_path);
    expect('random.jpg')->toEqual($supportingDocument->file_name);

    Storage::assertExists('supportingDocuments/' . $file->hashName());

    $response = $this->get('/members/' . $member->id . '/documents/' . $supportingDocument->id . '/download');
    $response->assertStatus(200);

    // Clean up
    Storage::delete('supportingDocuments/' . $file->hashName());
});
