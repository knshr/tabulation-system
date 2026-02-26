<?php

namespace App\Services;

use App\Enums\EventStatus;
use App\Events\ScoreSubmitted;
use App\Exceptions\InvalidScoreException;
use App\Models\Score;
use App\Repositories\Contracts\CriteriaRepositoryInterface;
use App\Repositories\Contracts\EventRepositoryInterface;
use App\Repositories\Contracts\ScoreRepositoryInterface;

class ScoringService
{
    public function __construct(
        private ScoreRepositoryInterface $scoreRepo,
        private CriteriaRepositoryInterface $criteriaRepo,
        private EventRepositoryInterface $eventRepo,
    ) {}

    public function submitScore(int $eventId, int $judgeId, int $contestantId, int $criteriaId, float $score, ?string $remarks = null): Score
    {
        $event = $this->eventRepo->findOrFail($eventId);

        if ($event->status !== EventStatus::Active) {
            throw new InvalidScoreException('Scores can only be submitted for active events.');
        }

        $criteria = $this->criteriaRepo->findOrFail($criteriaId);

        if ($score < 0 || $score > (float) $criteria->max_score) {
            throw new InvalidScoreException("Score must be between 0 and {$criteria->max_score}.");
        }

        $savedScore = $this->scoreRepo->save([
            'event_id' => $eventId,
            'judge_id' => $judgeId,
            'contestant_id' => $contestantId,
            'criteria_id' => $criteriaId,
            'score' => $score,
            'remarks' => $remarks,
        ]);

        event(new ScoreSubmitted($savedScore));

        return $savedScore;
    }
}
