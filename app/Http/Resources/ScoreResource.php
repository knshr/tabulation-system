<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScoreResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'event_id' => $this->event_id,
            'judge_id' => $this->judge_id,
            'contestant_id' => $this->contestant_id,
            'criteria_id' => $this->criteria_id,
            'score' => $this->score,
            'remarks' => $this->remarks,
            'judge' => $this->whenLoaded('judge'),
            'contestant' => $this->whenLoaded('contestant'),
            'criteria' => $this->whenLoaded('criteria'),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
