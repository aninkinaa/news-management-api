<?php

namespace App\Repositories\Implementations;

use App\Repositories\Interfaces\NewsRepositoryInterface;
use App\Models\News;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class NewsRepositoryImplement implements NewsRepositoryInterface
{
    protected $model;

    public function __construct(News $model)
    {
        $this->model = $model;
    }

    public function allPaginated(int $perPage = 10, array $relations = [], array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->with($relations);

        $query->withCount('comments');

        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (isset($filters['keyword'])) {
           $keyword = $filters['keyword'];

            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', '%' . $keyword . '%')
                  ->orWhere('content', 'like', '%' . $keyword . '%');
            });
        }
        
        return $query->latest()->paginate($perPage);
    }

    public function findWithComments(int $id): ?News
    {

        return $this->model->with(['comments', 'user'])->find($id);
    }

    public function findById(int $id): ?News
    {
        return $this->model->find($id);
    }

    public function create(array $data): News
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): News
    {
        $news = $this->findById($id);
        
        $news->update($data);
        return $news->fresh();
    }

    public function delete(int $id): bool
    {
        $news = $this->findById($id);
        if ($news) {
            return $news->delete();
        }
        return false;
    }
}