<?php

namespace App\Repositories;

use App\Models\Contestant;
use App\Repositories\Contracts\ContestantRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EloquentContestantRepository implements ContestantRepositoryInterface
{
    public function all(): Collection
    {
        return Contestant::orderBy('contestant_number')->get();
    }

    public function findOrFail(int $id): Contestant
    {
        return Contestant::findOrFail($id);
    }

    public function create(array $data): Contestant
    {
        return Contestant::create($data);
    }

    public function update(int $id, array $data): Contestant
    {
        $contestant = Contestant::findOrFail($id);
        $contestant->update($data);

        return $contestant->fresh();
    }

    public function delete(int $id): bool
    {
        return Contestant::findOrFail($id)->delete();
    }

    public function findByEvent(int $eventId): Collection
    {
        return Contestant::whereHas('events', fn ($q) => $q->where('event_id', $eventId))
            ->orderBy('contestant_number')
            ->get();
    }
}
