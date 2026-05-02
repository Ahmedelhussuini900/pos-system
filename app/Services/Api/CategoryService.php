<?php

namespace App\Services\Api;

use App\Repositories\Api\Contracts\CategoryRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class CategoryService
{
    public function __construct(private CategoryRepositoryInterface $categoryRepository)
    {
    }

    public function getAll()
    {
        return $this->categoryRepository->all();
    }

    public function findById(int $id)
    {
        return $this->categoryRepository->find($id);
    }

    public function create(array $data)
    {
        if (isset($data['image'])) {
            $data['image'] = $data['image']->store('categories', 'public');
        }

        return $this->categoryRepository->create($data);
    }

    public function update(int $id, array $data)
    {
        if (isset($data['image'])) {
            // امسح الصورة القديمة
            $category = $this->categoryRepository->find($id);
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }

            $data['image'] = $data['image']->store('categories', 'public');
        }

        return $this->categoryRepository->update($id, $data);
    }

    public function delete(int $id)
    {
        $category = $this->categoryRepository->find($id);
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        return $this->categoryRepository->delete($id);
    }
}
