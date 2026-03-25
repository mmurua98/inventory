<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductCategory\StoreProductCategoryRequest;
use App\Http\Requests\ProductCategory\UpdateProductCategoryRequest;
use App\Models\ProductCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductCategoryController extends Controller
{
    public function index(): View
    {
        return view('master-data.product-categories.index', [
            'productCategories' => ProductCategory::query()->latest('id')->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('master-data.product-categories.create');
    }

    public function store(StoreProductCategoryRequest $request): RedirectResponse
    {
        ProductCategory::query()->create($request->validated());

        return redirect()
            ->route('master-data.product-categories.index')
            ->with('success', 'Categoría creada correctamente.');
    }

    public function show(ProductCategory $productCategory): RedirectResponse
    {
        return redirect()->route('master-data.product-categories.edit', $productCategory);
    }

    public function edit(ProductCategory $productCategory): View
    {
        return view('master-data.product-categories.edit', [
            'productCategory' => $productCategory,
        ]);
    }

    public function update(UpdateProductCategoryRequest $request, ProductCategory $productCategory): RedirectResponse
    {
        $productCategory->update($request->validated());

        return redirect()
            ->route('master-data.product-categories.index')
            ->with('success', 'Categoría actualizada correctamente.');
    }

    public function destroy(ProductCategory $productCategory): RedirectResponse
    {
        $productCategory->delete();

        return redirect()
            ->route('master-data.product-categories.index')
            ->with('success', 'Categoría eliminada correctamente.');
    }
}
