<?php

namespace App\Services;

use App\Models\Event;
use App\Repositories\Contracts\EventRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EventService
{
    public function __construct(
        private EventRepositoryInterface $eventRepo,
    ) {}

    public function getAllEvents(): Collection
    {
        return $this->eventRepo->all();
    }

    public function getEvent(int $id): Event
    {
        return $this->eventRepo->findOrFail($id);
    }

    public function createEvent(array $data): Event
    {
        return $this->eventRepo->create($data);
    }

    public function updateEvent(int $id, array $data): Event
    {
        return $this->eventRepo->update($id, $data);
    }

    public function deleteEvent(int $id): bool
    {
        return $this->eventRepo->delete($id);
    }
}
