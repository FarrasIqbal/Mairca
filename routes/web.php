<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\CriteriaController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('positions', PositionController::class);

    Route::resource('positions.criteria', CriteriaController::class)->except(['show']);

    Route::resource('candidates', CandidateController::class)->except(['create', 'edit', 'show']);

    Route::get('/evaluations', [EvaluationController::class, 'index'])->name('evaluations.index');
    Route::post('/evaluations/bulk', [EvaluationController::class, 'storeBulk'])->name('evaluations.storeBulk');

    // Dashboard Ranking (HRD & Manajemen)
    Route::get('/rankings', [RankingController::class, 'index'])->name('rankings.index');
});

require __DIR__ . '/auth.php';
