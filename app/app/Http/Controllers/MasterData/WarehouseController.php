<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Http\Requests\Warehouse\StoreWarehouseRequest;
use App\Http\Requests\Warehouse\UpdateWarehouseRequest;
use App\Models\Warehouse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WarehouseController extends Controller
{
    public function index(): View
    {
        return view('master-data.warehouses.index', [
            'warehouses' => Warehouse::query()->latest('id')->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('master-data.warehouses.create');
    }

    public function store(StoreWarehouseRequest $request): RedirectResponse
    {
        Warehouse::query()->create($request->validated());

        return redirect()
            ->route('master-data.warehouses.index')
            ->with('success', 'Almacén creado correctamente.');
    }

    public function show(Warehouse $warehouse): RedirectResponse
    {
        return redirect()->route('master-data.warehouses.edit', $warehouse);
    }

    public function edit(Warehouse $warehouse): View
    {
        return view('master-data.warehouses.edit', [
            'warehouse' => $warehouse,
        ]);
    }

    public function update(UpdateWarehouseRequest $request, Warehouse $warehouse): RedirectResponse
    {
        $warehouse->update($request->validated());

        return redirect()
            ->route('master-data.warehouses.index')
            ->with('success', 'Almacén actualizado correctamente.');
    }

    public function destroy(Warehouse $warehouse): RedirectResponse
    {
        $warehouse->delete();

        return redirect()
            ->route('master-data.warehouses.index')
            ->with('success', 'Almacén eliminado correctamente.');
    }
}
