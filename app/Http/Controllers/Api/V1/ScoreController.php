<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreScoreRequest;
use App\Http\Resources\ScoreResource;
use App\Models\Event;
use App\Repositories\Contracts\ScoreQueryInterface;
use App\Services\CriteriaService;
use App\Services\EventService;
use App\Services\ScoringService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ScoreController extends Controller
{
    public function index(
        Request $request,
        Event $event,
        EventService $eventService,
        CriteriaService $criteriaService,
        ScoreQueryInterface $scoreQuery,
    ): JsonResponse {
        $event = $eventService->getEvent($event->id);
        $judgeId = $request->user()->id;

        return response()->json([
            'event' => $event,
            'contestants' => $event->contestants,
            'criteria' => $criteriaService->getCriteriaForEvent($event->id),
            'existing_scores' => ScoreResource::collection($scoreQuery->findByJudge($event->id, $judgeId)),
        ]);
    }

    public function store(StoreScoreRequest $request, Event $event, ScoringService $service): ScoreResource
    {
        $score = $service->submitScore(
            eventId: $event->id,
            judgeId: $request->user()->id,
            contestantId: $request->validated()['contestant_id'],
            criteriaId: $request->validated()['criteria_id'],
            score: $request->validated()['score'],
            remarks: $request->validated()['remarks'] ?? null,
        );

        return new ScoreResource($score);
    }

    public function scores(Event $event, ScoreQueryInterface $scoreQuery): AnonymousResourceCollection
    {
        return ScoreResource::collection($scoreQuery->findByEvent($event->id));
    }
}
