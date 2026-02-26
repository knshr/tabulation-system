<?php

namespace App\Repositories;

use App\Models\Criteria;
use App\Repositories\Contracts\CriteriaRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EloquentCriteriaRepository implements CriteriaRepositoryInterface
{
    public function findOrFail(int $id): Criteria
    {
        return Criteria::findOrFail($id);
    }

    public function create(array $data): Criteria
    {
        return Criteria::create($data);
    }

    public function update(int $id, array $data): Criteria
    {
        $criteria = Criteria::findOrFail($id);
        $criteria->update($data);
        return $criteria->fresh();
    }

    public function delete(int $id): bool
    {
        return Criteria::findOrFail($id)->delete();
    }

    public function findByEvent(int $eventId): Collection
    {
        return Criteria::where('event_id', $eventId)->orderBy('order')->get();
    }
}
