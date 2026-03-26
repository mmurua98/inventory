<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\TaxRate;
use App\Models\Unit;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        return view('master-data.products.index', [
            'products' => Product::query()->with(['category', 'unit', 'taxRate'])->latest('id')->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('master-data.products.create', [
            'categories' => ProductCategory::query()->where('is_active', true)->orderBy('name')->get(),
            'units' => Unit::query()->orderBy('name')->get(),
            'taxRates' => TaxRate::query()->where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        Product::query()->create($request->validated());

        return redirect()
            ->route('master-data.products.index')
            ->with('success', 'Producto creado correctamente.');
    }

    public function show(Product $product): RedirectResponse
    {
        return redirect()->route('master-data.products.edit', $product);
    }

    public function edit(Product $product): View
    {
        return view('master-data.products.edit', [
            'product' => $product,
            'categories' => ProductCategory::query()
                ->where('is_active', true)
                ->orWhere('id', $product->category_id)
                ->orderBy('name')
                ->get(),
            'units' => Unit::query()->orderBy('name')->get(),
            'taxRates' => TaxRate::query()
                ->where('is_active', true)
                ->orWhere('id', $product->tax_rate_id)
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $product->update($request->validated());

        return redirect()
            ->route('master-data.products.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()
            ->route('master-data.products.index')
            ->with('success', 'Producto eliminado correctamente.');
    }
}
