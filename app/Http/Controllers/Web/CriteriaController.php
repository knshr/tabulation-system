<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCriteriaRequest;
use App\Http\Requests\UpdateCriteriaRequest;
use App\Models\Criteria;
use App\Models\Event;
use App\Services\CriteriaService;
use App\Services\EventService;
use Inertia\Inertia;
use Inertia\Response;

class CriteriaController extends Controller
{
    public function index(Event $event, CriteriaService $criteriaService, EventService $eventService): Response
    {
        return Inertia::render('Criteria/Index', [
            'event' => $eventService->getEvent($event->id),
            'criteria' => $criteriaService->getCriteriaForEvent($event->id),
        ]);
    }

    public function store(StoreCriteriaRequest $request, Event $event, CriteriaService $service)
    {
        $service->createCriteria([
            ...$request->validated(),
            'event_id' => $event->id,
        ]);

        return redirect()->route('events.criteria.index', $event)->with('success', 'Criteria added successfully.');
    }

    public function update(UpdateCriteriaRequest $request, Event $event, Criteria $criterion, CriteriaService $service)
    {
        $service->updateCriteria($criterion->id, $request->validated());

        return redirect()->route('events.criteria.index', $event)->with('success', 'Criteria updated successfully.');
    }

    public function destroy(Event $event, Criteria $criterion, CriteriaService $service)
    {
        $service->deleteCriteria($criterion->id);

        return redirect()->route('events.criteria.index', $event)->with('success', 'Criteria deleted successfully.');
    }
}
