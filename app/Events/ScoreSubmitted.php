<?php

namespace App\Events;

use App\Models\Score;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ScoreSubmitted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Score $score) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("event.{$this->score->event_id}.scores"),
            new Channel("event.{$this->score->event_id}.scoreboard"),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'score' => $this->score->load(['judge', 'contestant', 'criteria'])->toArray(),
        ];
    }
}
