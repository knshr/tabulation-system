<?php

namespace App\Services;

use App\Models\Contestant;
use App\Models\Event;
use App\Repositories\Contracts\ContestantRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ContestantService
{
    public function __construct(
        private ContestantRepositoryInterface $contestantRepo,
    ) {}

    public function getContestantsForEvent(int $eventId): Collection
    {
        return $this->contestantRepo->findByEvent($eventId);
    }

    public function createContestant(array $data): Contestant
    {
        return $this->contestantRepo->create($data);
    }

    public function attachToEvent(Event $event, Contestant $contestant, int $order = 0): void
    {
        $event->contestants()->syncWithoutDetaching([
            $contestant->id => ['order' => $order],
        ]);
    }

    public function detachFromEvent(Event $event, int $contestantId): void
    {
        $event->contestants()->detach($contestantId);
    }

    public function deleteContestant(int $id): bool
    {
        return $this->contestantRepo->delete($id);
    }
}
