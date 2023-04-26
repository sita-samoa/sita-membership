<?php

use Inertia\Inertia;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MemberQualificationController;
use App\Http\Controllers\MemberSupportingDocumentController;
use App\Http\Controllers\SignupController;
use App\Http\Controllers\MemberRefereeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\MemberWorkExperienceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Sign up Page
    Route::get('/members/signup', [MemberController::class, 'signup'])
    ->name('members.signup');

    Route::post('/members/signup', [MemberController::class, 'storeSignup'])
    ->name('members.signup.store');

    // Member actions
    Route::put('/members/{member}/submit', [MemberController::class, 'submit'])
    ->name('members.submit');

    Route::put('/members/{member}/endorse', [MemberController::class, 'endorse'])
    ->name('members.endorse');

    Route::put('/members/{member}/accept', [MemberController::class, 'accept'])
    ->name('members.accept');

    // Member work experience
    Route::resource('member-work-experiences', MemberWorkExperienceController::class)->only([
        'store', 'update', 'destroy'
    ]);
});

// Signup
Route::resource('members.signup', SignupController::class)
    ->only(['index'])
    ->middleware([
        'auth:sanctum',
        config('jetstream.auth_session'),
        'verified'
]);

// Academic Qualifications
Route::resource('members.qualifications', MemberQualificationController::class)
    ->only(['store', 'update', 'destroy']);

// Referees
Route::resource('members.referees', MemberRefereeController::class)
    ->only(['store', 'update', 'destroy']);

// Supporting Documents
Route::resource('members.documents', MemberSupportingDocumentController::class)
    ->only(['store', 'update', 'destroy'])
    ->middleware([
        'auth:sanctum',
        config('jetstream.auth_session'),
        'verified'
]);

// Members Pages
Route::resource('members', MemberController::class)
    ->only(['store', 'show', 'update'])
    ->middleware([
        'auth:sanctum',
        config('jetstream.auth_session'),
        'verified'
]);
