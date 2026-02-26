<?php

namespace App\Reports;

use App\Models\Event;
use App\Reports\Contracts\ReportGeneratorInterface;
use App\Repositories\Contracts\ScoreQueryInterface;

class ContestantDetailReport implements ReportGeneratorInterface
{
    public function __construct(private ScoreQueryInterface $scoreQuery) {}

    public function generate(Event $event, array $options = []): array
    {
        $contestants = $event->contestants;
        $details = [];

        foreach ($contestants as $contestant) {
            $scores = $this->scoreQuery->findByContestant($event->id, $contestant->id);

            $details[] = [
                'contestant' => $contestant,
                'scores_by_criteria' => $scores->groupBy('criteria_id')->map(function ($criteriaScores) {
                    return [
                        'criteria' => $criteriaScores->first()->criteria,
                        'judge_scores' => $criteriaScores->map(fn ($s) => [
                            'judge' => $s->judge,
                            'score' => $s->score,
                            'remarks' => $s->remarks,
                        ]),
                        'average' => round($criteriaScores->avg('score'), 2),
                    ];
                })->values(),
                'total_average' => round($scores->avg('score'), 2),
            ];
        }

        return ['details' => $details, 'event' => $event];
    }

    public function getName(): string
    {
        return 'contestant-detail';
    }
}
