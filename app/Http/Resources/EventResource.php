<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'venue' => $this->venue,
            'event_date' => $this->event_date?->toIso8601String(),
            'status' => $this->status,
            'scoring_mode' => $this->scoring_mode,
            'created_by' => $this->created_by,
            'creator' => $this->whenLoaded('creator'),
            'contestants' => $this->whenLoaded('contestants'),
            'judges' => $this->whenLoaded('judges'),
            'criteria' => $this->whenLoaded('criteria'),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
