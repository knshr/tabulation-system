<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Models\Event;
use App\Services\EventService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EventController extends Controller
{
    public function index(EventService $service): AnonymousResourceCollection
    {
        return EventResource::collection($service->getAllEvents());
    }

    public function show(Event $event, EventService $service): EventResource
    {
        return new EventResource($service->getEvent($event->id));
    }
}
