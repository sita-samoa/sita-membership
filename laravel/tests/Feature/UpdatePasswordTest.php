<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('password can be updated', function () {
    $this->actingAs($user = User::factory()->create());
    $password = 'password';
    $new_password = 'fYbw0T6gCMuYf6Dd';
    $response = $this->put('/user/password', [
        'current_password' => $password,
        'password' => $new_password,
        'password_confirmation' => $new_password,
    ]);

    expect(Hash::check($new_password, $user->fresh()->password))->toBeTrue();
});

test('current password must be correct', function () {
    $this->actingAs($user = User::factory()->create());
    $new_password = 'fYbw0T6gCMuYf6Dd';

    $response = $this->put('/user/password', [
        'current_password' => 'wrong-password',
        'password' => $new_password,
        'password_confirmation' => $new_password,
    ]);

    $response->assertSessionHasErrors();

    expect(Hash::check('password', $user->fresh()->password))->toBeTrue();
});

test('new passwords must match', function () {
    $this->actingAs($user = User::factory()->create());
    $new_password = 'fYbw0T6gCMuYf6Dd';

    $response = $this->put('/user/password', [
        'current_password' => 'password',
        'password' => $new_password,
        'password_confirmation' => 'wrong-password',
    ]);

    $response->assertSessionHasErrors();

    expect(Hash::check('password', $user->fresh()->password))->toBeTrue();
});
