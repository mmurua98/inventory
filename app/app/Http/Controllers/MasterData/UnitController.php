<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Http\Requests\Unit\StoreUnitRequest;
use App\Http\Requests\Unit\UpdateUnitRequest;
use App\Models\Unit;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UnitController extends Controller
{
    public function index(): View
    {
        return view('master-data.units.index', [
            'units' => Unit::query()->latest('id')->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('master-data.units.create');
    }

    public function store(StoreUnitRequest $request): RedirectResponse
    {
        Unit::query()->create($request->validated());

        return redirect()
            ->route('master-data.units.index')
            ->with('success', 'Unidad creada correctamente.');
    }

    public function show(Unit $unit): RedirectResponse
    {
        return redirect()->route('master-data.units.edit', $unit);
    }

    public function edit(Unit $unit): View
    {
        return view('master-data.units.edit', [
            'unit' => $unit,
        ]);
    }

    public function update(UpdateUnitRequest $request, Unit $unit): RedirectResponse
    {
        $unit->update($request->validated());

        return redirect()
            ->route('master-data.units.index')
            ->with('success', 'Unidad actualizada correctamente.');
    }

    public function destroy(Unit $unit): RedirectResponse
    {
        $unit->delete();

        return redirect()
            ->route('master-data.units.index')
            ->with('success', 'Unidad eliminada correctamente.');
    }
}
