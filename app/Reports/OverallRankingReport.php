<?php

namespace App\Reports;

use App\Models\Event;
use App\Reports\Contracts\ReportGeneratorInterface;
use App\Repositories\Contracts\ScoreQueryInterface;

class OverallRankingReport implements ReportGeneratorInterface
{
    public function __construct(private ScoreQueryInterface $scoreQuery) {}

    public function generate(Event $event, array $options = []): array
    {
        $grouped = $this->scoreQuery->getAggregateByContestant($event->id);
        $criteria = $event->criteria;
        $rankings = [];

        foreach ($grouped as $contestantId => $scores) {
            $contestant = $scores->first()->contestant;
            $totalWeighted = 0;

            foreach ($criteria as $c) {
                $criteriaScores = $scores->where('criteria_id', $c->id);
                $avgScore = $criteriaScores->avg('score') ?? 0;
                $weightedScore = ($avgScore / (float) $c->max_score) * (float) $c->percentage_weight;
                $totalWeighted += $weightedScore;
            }

            $rankings[] = [
                'contestant' => $contestant,
                'total_weighted_score' => round($totalWeighted, 4),
            ];
        }

        usort($rankings, fn ($a, $b) => $b['total_weighted_score'] <=> $a['total_weighted_score']);

        foreach ($rankings as $i => &$r) {
            $r['rank'] = $i + 1;
        }

        return ['rankings' => $rankings, 'criteria' => $criteria];
    }

    public function getName(): string
    {
        return 'overall-ranking';
    }
}
