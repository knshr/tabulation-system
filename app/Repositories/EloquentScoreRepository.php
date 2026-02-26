<?php

namespace App\Repositories;

use App\Models\Score;
use App\Repositories\Contracts\ScoreQueryInterface;
use App\Repositories\Contracts\ScoreRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;

class EloquentScoreRepository implements ScoreRepositoryInterface, ScoreQueryInterface
{
    public function save(array $data): Score
    {
        return Score::updateOrCreate(
            Arr::only($data, ['event_id', 'judge_id', 'contestant_id', 'criteria_id']),
            Arr::only($data, ['score', 'remarks']),
        );
    }

    public function update(int $id, array $data): Score
    {
        $score = Score::findOrFail($id);
        $score->update($data);
        return $score->fresh();
    }

    public function delete(int $id): bool
    {
        return Score::findOrFail($id)->delete();
    }

    public function findByEvent(int $eventId): Collection
    {
        return Score::where('event_id', $eventId)
            ->with(['judge', 'contestant', 'criteria'])
            ->get();
    }

    public function findByJudge(int $eventId, int $judgeId): Collection
    {
        return Score::where('event_id', $eventId)
            ->where('judge_id', $judgeId)
            ->with(['contestant', 'criteria'])
            ->get();
    }

    public function findByContestant(int $eventId, int $contestantId): Collection
    {
        return Score::where('event_id', $eventId)
            ->where('contestant_id', $contestantId)
            ->with(['judge', 'criteria'])
            ->get();
    }

    public function findByCriteria(int $eventId, int $criteriaId): Collection
    {
        return Score::where('event_id', $eventId)
            ->where('criteria_id', $criteriaId)
            ->with(['judge', 'contestant'])
            ->get();
    }

    public function getAggregateByContestant(int $eventId): Collection
    {
        return Score::where('event_id', $eventId)
            ->with(['contestant', 'criteria'])
            ->get()
            ->groupBy('contestant_id');
    }

    public function getScoreMatrix(int $eventId): array
    {
        $scores = Score::where('event_id', $eventId)
            ->with(['judge', 'contestant', 'criteria'])
            ->get();

        $matrix = [];
        foreach ($scores as $score) {
            $matrix[$score->contestant_id][$score->judge_id][$score->criteria_id] = [
                'score' => $score->score,
                'remarks' => $score->remarks,
            ];
        }

        return $matrix;
    }
}
