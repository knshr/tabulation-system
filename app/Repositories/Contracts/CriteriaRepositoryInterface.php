<?php

namespace App\Repositories\Contracts;

use App\Models\Criteria;
use Illuminate\Database\Eloquent\Collection;

interface CriteriaRepositoryInterface
{
    public function findOrFail(int $id): Criteria;
    public function create(array $data): Criteria;
    public function update(int $id, array $data): Criteria;
    public function delete(int $id): bool;
    public function findByEvent(int $eventId): Collection;
}
