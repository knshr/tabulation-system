<?php

namespace App\Reports;

use App\Models\Event;
use App\Reports\Contracts\ReportGeneratorInterface;
use App\Repositories\Contracts\ScoreQueryInterface;

class ScoreComparisonReport implements ReportGeneratorInterface
{
    public function __construct(private ScoreQueryInterface $scoreQuery) {}

    public function generate(Event $event, array $options = []): array
    {
        $matrix = $this->scoreQuery->getScoreMatrix($event->id);
        $judges = $event->judges;
        $contestants = $event->contestants;
        $criteria = $event->criteria;

        $comparisons = [];

        foreach ($contestants as $contestant) {
            $judgeScores = [];

            foreach ($judges as $judge) {
                $total = 0;
                $criteriaDetails = [];

                foreach ($criteria as $c) {
                    $entry = $matrix[$contestant->id][$judge->id][$c->id] ?? null;
                    $score = $entry ? (float) $entry['score'] : 0;
                    $total += $score;
                    $criteriaDetails[] = [
                        'criteria' => $c->name,
                        'score' => $score,
                    ];
                }

                $judgeScores[] = [
                    'judge' => $judge,
                    'criteria_scores' => $criteriaDetails,
                    'total' => round($total, 2),
                ];
            }

            $totals = array_column($judgeScores, 'total');
            $comparisons[] = [
                'contestant' => $contestant,
                'judge_scores' => $judgeScores,
                'score_range' => count($totals) > 0 ? round(max($totals) - min($totals), 2) : 0,
            ];
        }

        return ['comparisons' => $comparisons, 'judges' => $judges, 'criteria' => $criteria];
    }

    public function getName(): string
    {
        return 'score-comparison';
    }
}
