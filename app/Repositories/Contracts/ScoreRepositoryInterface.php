<?php

namespace App\Repositories\Contracts;

use App\Models\Score;

interface ScoreRepositoryInterface
{
    public function save(array $data): Score;
    public function update(int $id, array $data): Score;
    public function delete(int $id): bool;
}
