<?php

use App\Http\Controllers\MasterData\ProductCategoryController;
use App\Http\Controllers\MasterData\ProductController;
use App\Http\Controllers\MasterData\SupplierController;
use App\Http\Controllers\MasterData\TaxRateController;
use App\Http\Controllers\MasterData\UnitController;
use App\Http\Controllers\MasterData\WarehouseController;
use Illuminate\Support\Facades\Route;

Route::prefix('master-data')->name('master-data.')->group(function () {
    Route::resource('product-categories', ProductCategoryController::class)
        ->except(['create', 'edit']);

    Route::resource('units', UnitController::class)
        ->except(['create', 'edit']);

    Route::resource('tax-rates', TaxRateController::class)
        ->except(['create', 'edit']);

    Route::resource('suppliers', SupplierController::class)
        ->except(['create', 'edit']);

    Route::resource('products', ProductController::class)
        ->except(['create', 'edit']);

    Route::resource('warehouses', WarehouseController::class)
        ->except(['create', 'edit']);
});
