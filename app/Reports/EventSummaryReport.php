<?php

namespace App\Reports;

use App\Models\Event;
use App\Reports\Contracts\ReportGeneratorInterface;
use App\Repositories\Contracts\ScoreQueryInterface;

class EventSummaryReport implements ReportGeneratorInterface
{
    public function __construct(private ScoreQueryInterface $scoreQuery) {}

    public function generate(Event $event, array $options = []): array
    {
        $scores = $this->scoreQuery->findByEvent($event->id);
        $totalJudges = $event->judges()->count();
        $totalContestants = $event->contestants()->count();
        $totalCriteria = $event->criteria()->count();
        $expectedScores = $totalJudges * $totalContestants * $totalCriteria;
        $actualScores = $scores->count();

        return [
            'event' => $event,
            'total_judges' => $totalJudges,
            'total_contestants' => $totalContestants,
            'total_criteria' => $totalCriteria,
            'expected_scores' => $expectedScores,
            'actual_scores' => $actualScores,
            'completion_percentage' => $expectedScores > 0 ? round(($actualScores / $expectedScores) * 100, 1) : 0,
            'average_score' => $scores->count() > 0 ? round($scores->avg('score'), 2) : 0,
            'highest_score' => $scores->count() > 0 ? (float) $scores->max('score') : 0,
            'lowest_score' => $scores->count() > 0 ? (float) $scores->min('score') : 0,
        ];
    }

    public function getName(): string
    {
        return 'event-summary';
    }
}
