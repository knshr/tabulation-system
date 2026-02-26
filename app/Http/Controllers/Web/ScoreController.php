<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Repositories\Contracts\ScoreQueryInterface;
use App\Services\EventService;
use Inertia\Inertia;
use Inertia\Response;

class ScoreController extends Controller
{
    public function index(Event $event, EventService $eventService, ScoreQueryInterface $scoreQuery): Response
    {
        $event = $eventService->getEvent($event->id);

        return Inertia::render('Scores/Index', [
            'event' => $event,
            'scores' => $scoreQuery->findByEvent($event->id),
        ]);
    }
}
