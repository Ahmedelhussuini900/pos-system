<?php

namespace App\Repositories\Api\Eloquent;

use App\Models\Category;
use App\Repositories\Api\Contracts\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function __construct(private Category $model)
    {
    }

    public function all(): Collection
    {
        return $this->model->orderBy('sort_order')->get();
    }

    public function find(int $id): Category
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data): Category
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Category
    {
        $category = $this->find($id);
        $category->update($data);

        return $category;
    }

    public function delete(int $id): bool
    {
        return $this->find($id)->delete();
    }
}
