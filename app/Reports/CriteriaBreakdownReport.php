<?php

namespace App\Reports;

use App\Models\Event;
use App\Reports\Contracts\ReportGeneratorInterface;
use App\Repositories\Contracts\ScoreQueryInterface;

class CriteriaBreakdownReport implements ReportGeneratorInterface
{
    public function __construct(private ScoreQueryInterface $scoreQuery) {}

    public function generate(Event $event, array $options = []): array
    {
        $criteria = $event->criteria;
        $breakdowns = [];

        foreach ($criteria as $c) {
            $scores = $this->scoreQuery->findByCriteria($event->id, $c->id);

            $breakdowns[] = [
                'criteria' => $c,
                'scores_by_contestant' => $scores->groupBy('contestant_id')->map(function ($contestantScores) {
                    return [
                        'contestant' => $contestantScores->first()->contestant,
                        'scores' => $contestantScores->pluck('score'),
                        'average' => round($contestantScores->avg('score'), 2),
                        'min' => $contestantScores->min('score'),
                        'max' => $contestantScores->max('score'),
                    ];
                })->values(),
                'overall_average' => round($scores->avg('score'), 2),
            ];
        }

        return ['breakdowns' => $breakdowns, 'event' => $event];
    }

    public function getName(): string
    {
        return 'criteria-breakdown';
    }
}
