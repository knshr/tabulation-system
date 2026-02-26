<?php

namespace App\Repositories;

use App\Models\Event;
use App\Repositories\Contracts\EventRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EloquentEventRepository implements EventRepositoryInterface
{
    public function all(): Collection
    {
        return Event::with('creator')->latest('event_date')->get();
    }

    public function findOrFail(int $id): Event
    {
        return Event::with(['creator', 'contestants', 'judges', 'criteria'])->findOrFail($id);
    }

    public function create(array $data): Event
    {
        return Event::create($data);
    }

    public function update(int $id, array $data): Event
    {
        $event = Event::findOrFail($id);
        $event->update($data);
        return $event->fresh();
    }

    public function delete(int $id): bool
    {
        return Event::findOrFail($id)->delete();
    }

    public function findByStatus(string $status): Collection
    {
        return Event::where('status', $status)->with('creator')->latest('event_date')->get();
    }
}
