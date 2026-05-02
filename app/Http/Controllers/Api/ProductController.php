<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Product\StoreProductRequest;
use App\Http\Requests\Api\Product\UpdateProductRequest;
use App\Http\Resources\Api\ProductResource;
use App\Services\Api\ProductService;

class ProductController extends Controller
{
    protected $service;

    public function __construct(ProductService $service)
    {
        $this->service = $service;
    }


      public function index()
    {
        return $this->success(
            ProductResource::collection($this->service->getAll())
        );
    }

    public function store(StoreProductRequest $request)
    {
        $record = $this->service->create($request->validated());

        return $this->created(new ProductResource($record));
    }

    public function show(int $id)
    {
        return $this->success(new ProductResource($this->service->findById($id)));
    }

    public function update(UpdateProductRequest $request, int $id)
    {
        $record = $this->service->update($id, $request->validated());

        return $this->success(new ProductResource($record));
    }

    public function destroy(int $id)
    {
        $this->service->delete($id);

        return $this->success(null, 'Product deleted successfully');
    }
}
