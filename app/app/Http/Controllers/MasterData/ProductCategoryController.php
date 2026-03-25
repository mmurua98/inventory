<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductCategory\StoreProductCategoryRequest;
use App\Http\Requests\ProductCategory\UpdateProductCategoryRequest;
use App\Models\ProductCategory;
use Illuminate\Http\JsonResponse;

class ProductCategoryController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(ProductCategory::query()->latest('id')->paginate());
    }

    public function store(StoreProductCategoryRequest $request): JsonResponse
    {
        $productCategory = ProductCategory::query()->create($request->validated());

        return response()->json($productCategory, 201);
    }

    public function show(ProductCategory $productCategory): JsonResponse
    {
        return response()->json($productCategory);
    }

    public function update(UpdateProductCategoryRequest $request, ProductCategory $productCategory): JsonResponse
    {
        $productCategory->update($request->validated());

        return response()->json($productCategory->fresh());
    }

    public function destroy(ProductCategory $productCategory): JsonResponse
    {
        $productCategory->delete();

        return response()->json([], 204);
    }
}
