<?php

namespace App\Providers;

use App\Reports\AuditLogReport;
use App\Reports\ContestantDetailReport;
use App\Reports\CriteriaBreakdownReport;
use App\Reports\EventSummaryReport;
use App\Reports\JudgeScoresheetReport;
use App\Reports\OverallRankingReport;
use App\Reports\ScoreComparisonReport;
use App\Repositories\Contracts\ContestantRepositoryInterface;
use App\Repositories\Contracts\CriteriaRepositoryInterface;
use App\Repositories\Contracts\EventRepositoryInterface;
use App\Repositories\Contracts\ScoreQueryInterface;
use App\Repositories\Contracts\ScoreRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\EloquentContestantRepository;
use App\Repositories\EloquentCriteriaRepository;
use App\Repositories\EloquentEventRepository;
use App\Repositories\EloquentScoreRepository;
use App\Repositories\EloquentUserRepository;
use App\Services\ReportService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(EventRepositoryInterface::class, EloquentEventRepository::class);
        $this->app->bind(ContestantRepositoryInterface::class, EloquentContestantRepository::class);
        $this->app->bind(CriteriaRepositoryInterface::class, EloquentCriteriaRepository::class);
        $this->app->bind(ScoreRepositoryInterface::class, EloquentScoreRepository::class);
        $this->app->bind(ScoreQueryInterface::class, EloquentScoreRepository::class);
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);

        $this->app->singleton(ReportService::class, function ($app) {
            $service = new ReportService;
            $service->registerGenerator($app->make(OverallRankingReport::class));
            $service->registerGenerator($app->make(JudgeScoresheetReport::class));
            $service->registerGenerator($app->make(ContestantDetailReport::class));
            $service->registerGenerator($app->make(CriteriaBreakdownReport::class));
            $service->registerGenerator($app->make(ScoreComparisonReport::class));
            $service->registerGenerator($app->make(EventSummaryReport::class));
            $service->registerGenerator($app->make(AuditLogReport::class));

            return $service;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
