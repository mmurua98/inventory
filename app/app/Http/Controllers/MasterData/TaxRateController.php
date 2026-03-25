<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaxRate\StoreTaxRateRequest;
use App\Http\Requests\TaxRate\UpdateTaxRateRequest;
use App\Models\TaxRate;
use Illuminate\Http\JsonResponse;

class TaxRateController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(TaxRate::query()->latest('id')->paginate());
    }

    public function store(StoreTaxRateRequest $request): JsonResponse
    {
        $taxRate = TaxRate::query()->create($request->validated());

        return response()->json($taxRate, 201);
    }

    public function show(TaxRate $taxRate): JsonResponse
    {
        return response()->json($taxRate);
    }

    public function update(UpdateTaxRateRequest $request, TaxRate $taxRate): JsonResponse
    {
        $taxRate->update($request->validated());

        return response()->json($taxRate->fresh());
    }

    public function destroy(TaxRate $taxRate): JsonResponse
    {
        $taxRate->delete();

        return response()->json([], 204);
    }
}
