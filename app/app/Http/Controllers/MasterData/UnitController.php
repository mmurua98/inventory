<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Http\Requests\Unit\StoreUnitRequest;
use App\Http\Requests\Unit\UpdateUnitRequest;
use App\Models\Unit;
use Illuminate\Http\JsonResponse;

class UnitController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Unit::query()->latest('id')->paginate());
    }

    public function store(StoreUnitRequest $request): JsonResponse
    {
        $unit = Unit::query()->create($request->validated());

        return response()->json($unit, 201);
    }

    public function show(Unit $unit): JsonResponse
    {
        return response()->json($unit);
    }

    public function update(UpdateUnitRequest $request, Unit $unit): JsonResponse
    {
        $unit->update($request->validated());

        return response()->json($unit->fresh());
    }

    public function destroy(Unit $unit): JsonResponse
    {
        $unit->delete();

        return response()->json([], 204);
    }
}
