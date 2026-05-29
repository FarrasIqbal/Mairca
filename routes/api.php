<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PositionApiController;
use App\Http\Controllers\Api\CriteriaApiController;
use App\Http\Controllers\Api\CandidateApiController;
use App\Http\Controllers\Api\InterviewApiController;
use App\Http\Controllers\Api\EvaluationApiController;
use App\Http\Controllers\Api\AdminPracticalTestApiController;
use App\Http\Controllers\Api\PublicTestApiController;
use App\Http\Controllers\Api\RankingApiController;
use App\Http\Controllers\Api\UserManagementApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (Unauthenticated)
|--------------------------------------------------------------------------
*/

Route::post('/login', [AuthController::class, 'login']);

// Public Candidate Practical Test
Route::get('/public/test/{token}', [PublicTestApiController::class, 'showTest']);
Route::post('/public/test/{token}/submit', [PublicTestApiController::class, 'submitTest']);

/*
|--------------------------------------------------------------------------
| Protected Routes (Requires Sanctum Token Authentication)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // Auth Actions
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Positions (Reviewers and HR can view, HR can manage)
    Route::get('/positions', [PositionApiController::class, 'index']);
    Route::get('/positions/{id}', [PositionApiController::class, 'show']);
    
    // Criteria (Reviewers and HR can view, HR can manage)
    Route::get('/positions/{position}/criteria', [CriteriaApiController::class, 'index']);

    // Candidates (Reviewers and HR can view, HR can manage)
    Route::get('/candidates', [CandidateApiController::class, 'index']);
    Route::get('/candidates/{id}', [CandidateApiController::class, 'show']);
    Route::get('/candidates/{id}/resume', [CandidateApiController::class, 'downloadResume']);

    // Interview Schedules (Reviewers and HR can view/update, HR can manage)
    Route::get('/interviews', [InterviewApiController::class, 'index']);
    Route::put('/interviews/{id}', [InterviewApiController::class, 'update']);

    // Evaluations (Reviewers and HR can submit and view)
    Route::get('/evaluations', [EvaluationApiController::class, 'index']);
    Route::post('/evaluations/bulk', [EvaluationApiController::class, 'storeBulk']);

    // Admin Practical Tests (Reviewers and HR can grade/view)
    Route::get('/admin/practical-tests', [AdminPracticalTestApiController::class, 'index']);
    Route::get('/admin/practical-tests/{candidate}/evaluate', [AdminPracticalTestApiController::class, 'evaluate']);
    Route::post('/admin/practical-tests/{candidate}/evaluate', [AdminPracticalTestApiController::class, 'storeEvaluation']);

    /*
    |--------------------------------------------------------------------------
    | HR-Only Protected API Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('api.hr-only')->group(function () {
        
        // Positions Management
        Route::post('/positions', [PositionApiController::class, 'store']);
        Route::put('/positions/{id}', [PositionApiController::class, 'update']);
        Route::delete('/positions/{id}', [PositionApiController::class, 'destroy']);

        // Criteria Management
        Route::post('/positions/{position}/criteria', [CriteriaApiController::class, 'store']);
        Route::put('/positions/{position}/criteria/{criterion}', [CriteriaApiController::class, 'update']);
        Route::delete('/positions/{position}/criteria/{criterion}', [CriteriaApiController::class, 'destroy']);

        // Candidates Management
        Route::post('/candidates', [CandidateApiController::class, 'store']);
        Route::put('/candidates/{id}', [CandidateApiController::class, 'update']);
        Route::delete('/candidates/{id}', [CandidateApiController::class, 'destroy']);

        // Interview Schedules Management
        Route::post('/interviews', [InterviewApiController::class, 'store']);
        Route::delete('/interviews/{id}', [InterviewApiController::class, 'destroy']);

        // Practical Tests Management
        Route::post('/admin/practical-tests/questions', [AdminPracticalTestApiController::class, 'storeQuestion']);
        Route::put('/admin/practical-tests/questions/{question}', [AdminPracticalTestApiController::class, 'updateQuestion']);
        Route::delete('/admin/practical-tests/questions/{question}', [AdminPracticalTestApiController::class, 'destroyQuestion']);
        Route::post('/admin/practical-tests/{candidate}/extend', [AdminPracticalTestApiController::class, 'extendTestLink']);

        // Real-Time MAIRCA Engine Ranking
        Route::get('/rankings', [RankingApiController::class, 'index']);

        // User Management
        Route::get('/users', [UserManagementApiController::class, 'index']);
        Route::post('/users', [UserManagementApiController::class, 'store']);
        Route::put('/users/{id}', [UserManagementApiController::class, 'update']);
        Route::delete('/users/{id}', [UserManagementApiController::class, 'destroy']);

    });

});
