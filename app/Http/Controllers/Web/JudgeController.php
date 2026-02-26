<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssignJudgeRequest;
use App\Models\Event;
use App\Models\User;
use App\Services\EventService;
use App\Services\UserService;
use Inertia\Inertia;
use Inertia\Response;

class JudgeController extends Controller
{
    public function index(Event $event, EventService $eventService, UserService $userService): Response
    {
        $event = $eventService->getEvent($event->id);

        return Inertia::render('Judges/Index', [
            'event' => $event,
            'assignedJudges' => $event->judges,
            'availableJudges' => $userService->getJudges(),
        ]);
    }

    public function assign(AssignJudgeRequest $request, Event $event)
    {
        $event->judges()->syncWithoutDetaching([$request->validated()['judge_id']]);

        return redirect()->route('events.judges.index', $event)->with('success', 'Judge assigned successfully.');
    }

    public function remove(Event $event, User $judge)
    {
        $event->judges()->detach($judge->id);

        return redirect()->route('events.judges.index', $event)->with('success', 'Judge removed successfully.');
    }
}
