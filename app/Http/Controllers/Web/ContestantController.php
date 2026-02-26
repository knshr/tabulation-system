<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContestantRequest;
use App\Models\Contestant;
use App\Models\Event;
use App\Services\ContestantService;
use App\Services\EventService;
use Inertia\Inertia;
use Inertia\Response;

class ContestantController extends Controller
{
    public function index(Event $event, ContestantService $contestantService, EventService $eventService): Response
    {
        return Inertia::render('Contestants/Index', [
            'event' => $eventService->getEvent($event->id),
            'contestants' => $contestantService->getContestantsForEvent($event->id),
        ]);
    }

    public function create(Event $event, EventService $eventService): Response
    {
        return Inertia::render('Contestants/Create', [
            'event' => $eventService->getEvent($event->id),
        ]);
    }

    public function store(StoreContestantRequest $request, Event $event, ContestantService $service)
    {
        $contestant = $service->createContestant($request->validated());
        $service->attachToEvent($event, $contestant, $request->input('order', 0));

        return redirect()->route('events.contestants.index', $event)->with('success', 'Contestant added successfully.');
    }

    public function destroy(Event $event, Contestant $contestant, ContestantService $service)
    {
        $service->detachFromEvent($event, $contestant->id);

        return redirect()->route('events.contestants.index', $event)->with('success', 'Contestant removed from event.');
    }
}
