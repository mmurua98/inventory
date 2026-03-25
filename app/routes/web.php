<?php

use App\Http\Controllers\MasterData\ProductCategoryController;
use App\Http\Controllers\MasterData\ProductController;
use App\Http\Controllers\MasterData\SupplierController;
use App\Http\Controllers\MasterData\TaxRateController;
use App\Http\Controllers\MasterData\UnitController;
use App\Http\Controllers\MasterData\WarehouseController;
use Illuminate\Support\Facades\Route;

Route::prefix('master-data')->name('master-data.')->group(function () {
    Route::resource('product-categories', ProductCategoryController::class);
    Route::resource('units', UnitController::class);
    Route::resource('tax-rates', TaxRateController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('products', ProductController::class);
    Route::resource('warehouses', WarehouseController::class);
});
