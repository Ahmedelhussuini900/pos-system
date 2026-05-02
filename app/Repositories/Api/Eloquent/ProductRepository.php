<?php

namespace App\Repositories\Api\Eloquent;

use App\Repositories\Api\Contracts\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Category;
use App\Models\Product;


class ProductRepository implements ProductRepositoryInterface
{
    // implement methods
      public function __construct(private Product $model)
    {
    }
     public function all(): Collection
    {
        return $this->model->orderBy('sort_order')->get();
    }

    public function find(int $id): Product
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data): Product
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Product
    {
        $product = $this->find($id);
        $product->update($data);

        return $product;
    }

    public function delete(int $id): bool
    {
        return $this->find($id)->delete();
    }
}
