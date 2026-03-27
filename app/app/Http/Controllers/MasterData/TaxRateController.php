<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaxRate\StoreTaxRateRequest;
use App\Http\Requests\TaxRate\UpdateTaxRateRequest;
use App\Models\TaxRate;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TaxRateController extends Controller
{
    public function index(): View
    {
        return view('master-data.tax-rates.index', [
            'taxRates' => TaxRate::query()->latest('id')->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('master-data.tax-rates.create');
    }

    public function store(StoreTaxRateRequest $request): RedirectResponse
    {
        TaxRate::query()->create($request->validated());

        return redirect()
            ->route('master-data.tax-rates.index')
            ->with('success', 'Tasa de impuesto creada correctamente.');
    }

    public function show(TaxRate $taxRate): RedirectResponse
    {
        return redirect()->route('master-data.tax-rates.edit', $taxRate);
    }

    public function edit(TaxRate $taxRate): View
    {
        return view('master-data.tax-rates.edit', [
            'taxRate' => $taxRate,
        ]);
    }

    public function update(UpdateTaxRateRequest $request, TaxRate $taxRate): RedirectResponse
    {
        $taxRate->update($request->validated());

        return redirect()
            ->route('master-data.tax-rates.index')
            ->with('success', 'Tasa de impuesto actualizada correctamente.');
    }

    public function destroy(TaxRate $taxRate): RedirectResponse
    {
        $taxRate->delete();

        return redirect()
            ->route('master-data.tax-rates.index')
            ->with('success', 'Tasa de impuesto eliminada correctamente.');
    }
}
