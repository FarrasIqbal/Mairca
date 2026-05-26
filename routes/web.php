<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\CriteriaController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InterviewScheduleController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\AdminPracticalTestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Jadwal Wawancara (semua role bisa lihat, HR bisa kelola)
    Route::resource('interviews', InterviewScheduleController::class)->except(['show']);

    // Evaluasi / Penilaian
    Route::get('/evaluations', [EvaluationController::class, 'index'])->name('evaluations.index');
    Route::post('/evaluations/bulk', [EvaluationController::class, 'storeBulk'])->name('evaluations.storeBulk');

    // Manajemen Tes Praktis (HR & Reviewer)
    Route::get('/admin/practical-tests', [AdminPracticalTestController::class, 'index'])->name('admin.practical-tests.index');
    Route::get('/admin/practical-tests/{candidate}/evaluate', [AdminPracticalTestController::class, 'evaluate'])->name('admin.practical-tests.evaluate');
    Route::post('/admin/practical-tests/{candidate}/evaluate', [AdminPracticalTestController::class, 'storeEvaluation'])->name('admin.practical-tests.store-evaluation');

    // HR-only routes
    Route::middleware('hr-only')->group(function () {
        Route::post('/admin/practical-tests/questions', [AdminPracticalTestController::class, 'storeQuestion'])->name('admin.practical-tests.questions.store');
        Route::put('/admin/practical-tests/questions/{question}', [AdminPracticalTestController::class, 'updateQuestion'])->name('admin.practical-tests.questions.update');
        Route::delete('/admin/practical-tests/questions/{question}', [AdminPracticalTestController::class, 'destroyQuestion'])->name('admin.practical-tests.questions.destroy');
        Route::post('/admin/practical-tests/positions/{position}/duration', [AdminPracticalTestController::class, 'updatePositionDuration'])->name('admin.practical-tests.positions.duration');
        Route::post('/admin/practical-tests/{candidate}/extend', [AdminPracticalTestController::class, 'extendTestLink'])->name('admin.practical-tests.candidates.extend');

        Route::resource('positions', PositionController::class);
        Route::resource('positions.criteria', CriteriaController::class)->except(['show']);
        Route::resource('candidates', CandidateController::class)->except(['create', 'edit']);
        Route::get('/candidates/{candidate}/resume', [CandidateController::class, 'downloadResume'])->name('candidates.resume');
        Route::get('/rankings', [RankingController::class, 'index'])->name('rankings.index');
        Route::resource('users', UserManagementController::class)->except(['show', 'create', 'edit']);
    });
});

// Public Candidate Practical Test routes
use App\Http\Controllers\PracticalTestController;
Route::get('/test/{token}', [PracticalTestController::class, 'showTest'])->name('public.test.show');
Route::post('/test/{token}/submit', [PracticalTestController::class, 'submitTest'])->name('public.test.submit');

require __DIR__ . '/auth.php';
