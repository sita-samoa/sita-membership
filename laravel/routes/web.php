<?php

use App\Http\Controllers\AuditController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\MailingListController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MemberQualificationController;
use App\Http\Controllers\MemberRefereeController;
use App\Http\Controllers\MemberSupportingDocumentController;
use App\Http\Controllers\MemberWorkExperienceController;
use App\Http\Controllers\SignupController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/demo', function () {
    return Inertia::render('Demo/HomeView');
})->middleware(['auth', 'verified'])->name('demo');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::resource('dashboard', DashboardController::class)
        ->only(['index']);

    // Sign up Page
    Route::get('/members/signup', [MemberController::class, 'signup'])
        ->name('members.signup');

    Route::post('/members/signup', [MemberController::class, 'storeSignup'])
        ->name('members.signup.store');

    // Member export
    Route::get('/members/export', [MemberController::class, 'export'])
        ->name('members.export');

    // Member actions
    Route::put('/members/{member}/submit', [MemberController::class, 'submit'])
        ->name('members.submit');

    Route::put('/members/{member}/endorse', [MemberController::class, 'endorse'])
        ->name('members.endorse');

    Route::put('/members/{member}/accept', [MemberController::class, 'accept'])
        ->name('members.accept');

    Route::put('/members/{member}/send-sub-reminder', [MemberController::class, 'sendSubReminder'])
        ->name('members.send-sub-reminder');

    Route::put('/members/{member}/send-past-due-sub-reminder', [MemberController::class, 'sendPastDueSubReminder'])
        ->name('members.send-past-due-sub-reminder');

    Route::put('/members/{member}/subscribe', [MemberController::class, 'toggleMailingListSubscription'])
        ->name('members.subscribe');

    Route::put('/members/{member}/view-flag', [MemberController::class, 'markOptionalFlagAsViewed'])
        ->name('members.view-flag');

    // Member work experience
    Route::resource('member-work-experiences', MemberWorkExperienceController::class)
        ->only(['store', 'update', 'destroy']);

    // Signup
    Route::resource('members.signup', SignupController::class)
        ->only(['index']);

    // Academic Qualifications
    Route::resource('members.qualifications', MemberQualificationController::class)
        ->only(['store', 'update', 'destroy']);

    // Work experience
    Route::resource('member-work-experiences', MemberWorkExperienceController::class)
        ->only(['store', 'update', 'destroy']);

    // Member audit
    Route::resource('members.audit', AuditController::class)
        ->only(['index']);

    // Referees
    Route::resource('members.referees', MemberRefereeController::class)
        ->only(['store', 'update', 'destroy']);

    // Supporting Documents
    Route::resource('members.documents', MemberSupportingDocumentController::class)
        ->only(['store', 'update', 'destroy']);

    // Supporting Documents download
    Route::resource('members.documents.download', DownloadController::class)
        ->only(['index']);

    // Members Pages
    Route::resource('members', MemberController::class)
        ->only(['store', 'show', 'update', 'index']);

    // Mailing List
    Route::resource('mailing-lists', MailingListController::class)
        ->only(['index']);
});
