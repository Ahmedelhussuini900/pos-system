<?php

namespace App\Services\Api;

use App\Repositories\Api\Contracts\ProductRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

     public function getAll()
    {
        return $this->productRepository->all();
    }

    public function findById(int $id)
    {
        return $this->productRepository->find($id);
    }

    public function create(array $data)
    {
        if (isset($data['image'])) {
            $data['image'] = $data['image']->store('products', 'public');
        }

        return $this->productRepository->create($data);
    }

    public function update(int $id, array $data)
    {
        if (isset($data['image'])) {
            // امسح الصورة القديمة
            $product = $this->productRepository->find($id);
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $data['image'] = $data['image']->store('products', 'public');
        }

        return $this->productRepository->update($id, $data);
    }

    public function delete(int $id)
    {
        $product = $this->productRepository->find($id);
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        return $this->productRepository->delete($id);
    }
}
