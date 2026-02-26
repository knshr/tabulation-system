<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Services\EventService;
use App\Services\ReportService;
use Inertia\Inertia;
use Inertia\Response;

class ScoreboardController extends Controller
{
    public function show(Event $event, EventService $eventService, ReportService $reportService): Response
    {
        $event = $eventService->getEvent($event->id);

        return Inertia::render('Scoreboard/Show', [
            'event' => $event,
            'rankings' => $reportService->generate('overall-ranking', $event),
        ]);
    }
}
