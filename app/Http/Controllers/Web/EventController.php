<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use App\Services\EventService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EventController extends Controller
{
    public function index(EventService $service): Response
    {
        return Inertia::render('Events/Index', [
            'events' => $service->getAllEvents(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Events/Create');
    }

    public function store(StoreEventRequest $request, EventService $service)
    {
        $event = $service->createEvent([
            ...$request->validated(),
            'created_by' => $request->user()->id,
        ]);

        return redirect()->route('events.show', $event)->with('success', 'Event created successfully.');
    }

    public function show(Event $event, EventService $service): Response
    {
        $event = $service->getEvent($event->id);

        return Inertia::render('Events/Show', [
            'event' => $event,
        ]);
    }

    public function edit(Event $event, EventService $service): Response
    {
        $event = $service->getEvent($event->id);

        return Inertia::render('Events/Edit', [
            'event' => $event,
        ]);
    }

    public function update(UpdateEventRequest $request, Event $event, EventService $service)
    {
        $service->updateEvent($event->id, $request->validated());

        return redirect()->route('events.show', $event)->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event, EventService $service)
    {
        $service->deleteEvent($event->id);

        return redirect()->route('events.index')->with('success', 'Event deleted successfully.');
    }
}
