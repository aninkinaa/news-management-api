<?php

namespace App\Repositories\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\News;

interface NewsRepositoryInterface
{

    public function allPaginated(int $perPage = 10, array $relations = []): LengthAwarePaginator;

    public function findWithComments(int $id): ?News;

    public function findById(int $id): ?News;

    public function create(array $data): News;

    public function update(int $id, array $data): News;

    public function delete(int $id): bool;
}