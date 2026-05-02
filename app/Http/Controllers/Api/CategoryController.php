<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Category\StoreCategoryRequest;
use App\Http\Requests\Api\Category\UpdateCategoryRequest;
use App\Http\Resources\Api\CategoryResource;
use App\Services\Api\CategoryService;
use App\Traits\ApiResponse;

class CategoryController extends Controller
{
    use ApiResponse;

    public function __construct(private CategoryService $service)
    {
    }

    public function index()
    {
        return $this->success(
            CategoryResource::collection($this->service->getAll())
        );
    }

    public function store(StoreCategoryRequest $request)
    {
        $record = $this->service->create($request->validated());

        return $this->created(new CategoryResource($record));
    }

    public function show(int $id)
    {
        return $this->success(new CategoryResource($this->service->findById($id)));
    }

    public function update(UpdateCategoryRequest $request, int $id)
    {
        $record = $this->service->update($id, $request->validated());

        return $this->success(new CategoryResource($record));
    }

    public function destroy(int $id)
    {
        $this->service->delete($id);

        return $this->success(null, 'Category deleted successfully');
    }
}
