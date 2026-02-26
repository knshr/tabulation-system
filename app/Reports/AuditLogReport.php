<?php

namespace App\Reports;

use App\Models\Event;
use App\Models\Score;
use App\Reports\Contracts\ReportGeneratorInterface;

class AuditLogReport implements ReportGeneratorInterface
{
    public function generate(Event $event, array $options = []): array
    {
        $scores = Score::where('event_id', $event->id)
            ->with(['judge', 'contestant', 'criteria'])
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(fn ($score) => [
                'id' => $score->id,
                'judge' => $score->judge,
                'contestant' => $score->contestant,
                'criteria' => $score->criteria,
                'score' => $score->score,
                'remarks' => $score->remarks,
                'created_at' => $score->created_at,
                'updated_at' => $score->updated_at,
                'was_edited' => $score->created_at->ne($score->updated_at),
            ]);

        return ['entries' => $scores, 'event' => $event];
    }

    public function getName(): string
    {
        return 'audit-log';
    }
}
