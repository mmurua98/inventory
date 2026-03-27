<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Http\Requests\Supplier\StoreSupplierRequest;
use App\Http\Requests\Supplier\UpdateSupplierRequest;
use App\Models\Supplier;
use App\Models\TaxRate;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SupplierController extends Controller
{
    public function index(): View
    {
        return view('master-data.suppliers.index', [
            'suppliers' => Supplier::query()->with('defaultTaxRate')->latest('id')->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('master-data.suppliers.create', [
            'taxRates' => TaxRate::query()->where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(StoreSupplierRequest $request): RedirectResponse
    {
        Supplier::query()->create($request->validated());

        return redirect()
            ->route('master-data.suppliers.index')
            ->with('success', 'Proveedor creado correctamente.');
    }

    public function show(Supplier $supplier): RedirectResponse
    {
        return redirect()->route('master-data.suppliers.edit', $supplier);
    }

    public function edit(Supplier $supplier): View
    {
        return view('master-data.suppliers.edit', [
            'supplier' => $supplier,
            'taxRates' => TaxRate::query()
                ->where('is_active', true)
                ->orWhere('id', $supplier->default_tax_rate_id)
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function update(UpdateSupplierRequest $request, Supplier $supplier): RedirectResponse
    {
        $supplier->update(collect($request->validated())->except('supplier_code')->all());

        return redirect()
            ->route('master-data.suppliers.index')
            ->with('success', 'Proveedor actualizado correctamente.');
    }

    public function destroy(Supplier $supplier): RedirectResponse
    {
        $supplier->delete();

        return redirect()
            ->route('master-data.suppliers.index')
            ->with('success', 'Proveedor eliminado correctamente.');
    }
}
