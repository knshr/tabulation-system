<?php

namespace App\Services;

use App\Models\Criteria;
use App\Repositories\Contracts\CriteriaRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CriteriaService
{
    public function __construct(
        private CriteriaRepositoryInterface $criteriaRepo,
    ) {}

    public function getCriteriaForEvent(int $eventId): Collection
    {
        return $this->criteriaRepo->findByEvent($eventId);
    }

    public function createCriteria(array $data): Criteria
    {
        return $this->criteriaRepo->create($data);
    }

    public function updateCriteria(int $id, array $data): Criteria
    {
        return $this->criteriaRepo->update($id, $data);
    }

    public function deleteCriteria(int $id): bool
    {
        return $this->criteriaRepo->delete($id);
    }
}
