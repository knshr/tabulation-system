<?php

namespace App\Repositories\Contracts;

use App\Models\Contestant;
use Illuminate\Database\Eloquent\Collection;

interface ContestantRepositoryInterface
{
    public function all(): Collection;
    public function findOrFail(int $id): Contestant;
    public function create(array $data): Contestant;
    public function update(int $id, array $data): Contestant;
    public function delete(int $id): bool;
    public function findByEvent(int $eventId): Collection;
}
