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

    // HR-only routes
    Route::middleware('hr-only')->group(function () {
        Route::resource('positions', PositionController::class);
        Route::resource('positions.criteria', CriteriaController::class)->except(['show']);
        Route::resource('candidates', CandidateController::class)->except(['create', 'edit', 'show']);
        Route::get('/rankings', [RankingController::class, 'index'])->name('rankings.index');
        Route::resource('users', UserManagementController::class)->except(['show', 'create', 'edit']);
    });
});

require __DIR__ . '/auth.php';
