<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Services\EventService;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ReportController extends Controller
{
    public function overallRanking(Event $event, EventService $eventService, ReportService $reportService): Response
    {
        return Inertia::render('Reports/OverallRanking', [
            'event' => $eventService->getEvent($event->id),
            'report' => $reportService->generate('overall-ranking', $event),
        ]);
    }

    public function judgeScoresheet(Request $request, Event $event, EventService $eventService, ReportService $reportService): Response
    {
        return Inertia::render('Reports/JudgeScoresheet', [
            'event' => $eventService->getEvent($event->id),
            'report' => $reportService->generate('judge-scoresheet', $event, [
                'judge_id' => $request->query('judge_id'),
            ]),
        ]);
    }

    public function contestantDetail(Event $event, EventService $eventService, ReportService $reportService): Response
    {
        return Inertia::render('Reports/ContestantDetail', [
            'event' => $eventService->getEvent($event->id),
            'report' => $reportService->generate('contestant-detail', $event),
        ]);
    }

    public function criteriaBreakdown(Event $event, EventService $eventService, ReportService $reportService): Response
    {
        return Inertia::render('Reports/CriteriaBreakdown', [
            'event' => $eventService->getEvent($event->id),
            'report' => $reportService->generate('criteria-breakdown', $event),
        ]);
    }

    public function scoreComparison(Event $event, EventService $eventService, ReportService $reportService): Response
    {
        return Inertia::render('Reports/ScoreComparison', [
            'event' => $eventService->getEvent($event->id),
            'report' => $reportService->generate('score-comparison', $event),
        ]);
    }

    public function eventSummary(Event $event, EventService $eventService, ReportService $reportService): Response
    {
        return Inertia::render('Reports/EventSummary', [
            'event' => $eventService->getEvent($event->id),
            'report' => $reportService->generate('event-summary', $event),
        ]);
    }

    public function auditLog(Event $event, EventService $eventService, ReportService $reportService): Response
    {
        return Inertia::render('Reports/AuditLog', [
            'event' => $eventService->getEvent($event->id),
            'report' => $reportService->generate('audit-log', $event),
        ]);
    }
}
