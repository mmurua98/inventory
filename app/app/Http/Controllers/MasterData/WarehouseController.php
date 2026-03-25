<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Http\Requests\Warehouse\StoreWarehouseRequest;
use App\Http\Requests\Warehouse\UpdateWarehouseRequest;
use App\Models\Warehouse;
use Illuminate\Http\JsonResponse;

class WarehouseController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Warehouse::query()->latest('id')->paginate());
    }

    public function store(StoreWarehouseRequest $request): JsonResponse
    {
        $warehouse = Warehouse::query()->create($request->validated());

        return response()->json($warehouse, 201);
    }

    public function show(Warehouse $warehouse): JsonResponse
    {
        return response()->json($warehouse);
    }

    public function update(UpdateWarehouseRequest $request, Warehouse $warehouse): JsonResponse
    {
        $warehouse->update($request->validated());

        return response()->json($warehouse->fresh());
    }

    public function destroy(Warehouse $warehouse): JsonResponse
    {
        $warehouse->delete();

        return response()->json([], 204);
    }
}
