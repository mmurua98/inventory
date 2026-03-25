<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Http\Requests\Supplier\StoreSupplierRequest;
use App\Http\Requests\Supplier\UpdateSupplierRequest;
use App\Models\Supplier;
use Illuminate\Http\JsonResponse;

class SupplierController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Supplier::query()->latest('id')->paginate());
    }

    public function store(StoreSupplierRequest $request): JsonResponse
    {
        $supplier = Supplier::query()->create($request->validated());

        return response()->json($supplier, 201);
    }

    public function show(Supplier $supplier): JsonResponse
    {
        return response()->json($supplier);
    }

    public function update(UpdateSupplierRequest $request, Supplier $supplier): JsonResponse
    {
        $supplier->update($request->validated());

        return response()->json($supplier->fresh());
    }

    public function destroy(Supplier $supplier): JsonResponse
    {
        $supplier->delete();

        return response()->json([], 204);
    }
}
