<?php

use App\Http\Controllers\Web\ContestantController;
use App\Http\Controllers\Web\CriteriaController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\EventController;
use App\Http\Controllers\Web\JudgeController;
use App\Http\Controllers\Web\LoginController;
use App\Http\Controllers\Web\ReportController;
use App\Http\Controllers\Web\ScoreboardController;
use App\Http\Controllers\Web\ScoreController;
use App\Http\Controllers\Web\ScoringController;
use App\Http\Controllers\Web\UserController;
use Illuminate\Support\Facades\Route;

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

// Public scoreboard
Route::get('/scoreboard/{event}', [ScoreboardController::class, 'show'])->name('scoreboard.show');

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Events (SuperAdmin, Admin)
    Route::middleware('role:super_admin,admin')->group(function () {
        Route::resource('events', EventController::class);

        // Nested under events
        Route::prefix('events/{event}')->name('events.')->group(function () {
            // Contestants
            Route::get('contestants', [ContestantController::class, 'index'])->name('contestants.index');
            Route::get('contestants/create', [ContestantController::class, 'create'])->name('contestants.create');
            Route::post('contestants', [ContestantController::class, 'store'])->name('contestants.store');
            Route::delete('contestants/{contestant}', [ContestantController::class, 'destroy'])->name('contestants.destroy');

            // Criteria
            Route::get('criteria', [CriteriaController::class, 'index'])->name('criteria.index');
            Route::post('criteria', [CriteriaController::class, 'store'])->name('criteria.store');
            Route::put('criteria/{criterion}', [CriteriaController::class, 'update'])->name('criteria.update');
            Route::delete('criteria/{criterion}', [CriteriaController::class, 'destroy'])->name('criteria.destroy');

            // Judges
            Route::get('judges', [JudgeController::class, 'index'])->name('judges.index');
            Route::post('judges', [JudgeController::class, 'assign'])->name('judges.assign');
            Route::delete('judges/{judge}', [JudgeController::class, 'remove'])->name('judges.remove');

            // Scores (view all)
            Route::get('scores', [ScoreController::class, 'index'])->name('scores.index');
        });

        // Reports
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('overall-ranking/{event}', [ReportController::class, 'overallRanking'])->name('overall-ranking');
            Route::get('judge-scoresheet/{event}', [ReportController::class, 'judgeScoresheet'])->name('judge-scoresheet');
            Route::get('contestant-detail/{event}', [ReportController::class, 'contestantDetail'])->name('contestant-detail');
            Route::get('criteria-breakdown/{event}', [ReportController::class, 'criteriaBreakdown'])->name('criteria-breakdown');
            Route::get('score-comparison/{event}', [ReportController::class, 'scoreComparison'])->name('score-comparison');
            Route::get('event-summary/{event}', [ReportController::class, 'eventSummary'])->name('event-summary');
        });
    });

    // Audit log (SuperAdmin only)
    Route::get('reports/audit-log/{event}', [ReportController::class, 'auditLog'])
        ->middleware('role:super_admin')
        ->name('reports.audit-log');

    // Scoring (Judge)
    Route::middleware('role:judge')->group(function () {
        Route::get('events/{event}/scoring', [ScoringController::class, 'index'])->name('events.scoring.index');
        Route::post('events/{event}/scoring', [ScoringController::class, 'store'])->name('events.scoring.store');
    });

    // Users (SuperAdmin)
    Route::middleware('role:super_admin')->group(function () {
        Route::resource('users', UserController::class)->except(['show', 'edit']);
    });
});
