<?php

namespace App\Reports;

use App\Models\Event;
use App\Reports\Contracts\ReportGeneratorInterface;
use App\Repositories\Contracts\ScoreQueryInterface;

class JudgeScoresheetReport implements ReportGeneratorInterface
{
    public function __construct(private ScoreQueryInterface $scoreQuery) {}

    public function generate(Event $event, array $options = []): array
    {
        $judgeId = $options['judge_id'] ?? null;
        $judges = $event->judges;
        $sheets = [];

        foreach ($judges as $judge) {
            if ($judgeId && $judge->id !== $judgeId) {
                continue;
            }

            $scores = $this->scoreQuery->findByJudge($event->id, $judge->id);

            $sheets[] = [
                'judge' => $judge,
                'scores' => $scores->groupBy('contestant_id')->map(function ($contestantScores) {
                    return [
                        'contestant' => $contestantScores->first()->contestant,
                        'criteria_scores' => $contestantScores->map(fn ($s) => [
                            'criteria' => $s->criteria,
                            'score' => $s->score,
                            'remarks' => $s->remarks,
                        ]),
                    ];
                })->values(),
            ];
        }

        return ['sheets' => $sheets, 'event' => $event];
    }

    public function getName(): string
    {
        return 'judge-scoresheet';
    }
}
