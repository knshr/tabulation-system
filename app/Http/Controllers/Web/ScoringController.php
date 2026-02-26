<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreScoreRequest;
use App\Models\Event;
use App\Repositories\Contracts\ScoreQueryInterface;
use App\Services\CriteriaService;
use App\Services\EventService;
use App\Services\ScoringService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ScoringController extends Controller
{
    public function index(
        Request $request,
        Event $event,
        EventService $eventService,
        CriteriaService $criteriaService,
        ScoreQueryInterface $scoreQuery,
    ): Response {
        $event = $eventService->getEvent($event->id);
        $judgeId = $request->user()->id;

        return Inertia::render('Scoring/Index', [
            'event' => $event,
            'contestants' => $event->contestants,
            'criteria' => $criteriaService->getCriteriaForEvent($event->id),
            'existingScores' => $scoreQuery->findByJudge($event->id, $judgeId),
        ]);
    }

    public function store(StoreScoreRequest $request, Event $event, ScoringService $service)
    {
        $service->submitScore(
            eventId: $event->id,
            judgeId: $request->user()->id,
            contestantId: $request->validated()['contestant_id'],
            criteriaId: $request->validated()['criteria_id'],
            score: $request->validated()['score'],
            remarks: $request->validated()['remarks'] ?? null,
        );

        return back()->with('success', 'Score submitted successfully.');
    }
}
