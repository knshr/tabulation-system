<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface ScoreQueryInterface
{
    public function findByEvent(int $eventId): Collection;
    public function findByJudge(int $eventId, int $judgeId): Collection;
    public function findByContestant(int $eventId, int $contestantId): Collection;
    public function findByCriteria(int $eventId, int $criteriaId): Collection;
    public function getAggregateByContestant(int $eventId): Collection;
    public function getScoreMatrix(int $eventId): array;
}
