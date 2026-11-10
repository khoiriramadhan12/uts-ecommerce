<?php

use App\Modules\Catalog\Http\Controllers\Admin\CategoryController;
use App\Modules\Catalog\Http\Controllers\Admin\ProductController;
use App\Modules\Catalog\Http\Controllers\Admin\TagController;
use App\Modules\Catalog\Http\Controllers\PublicProductController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::get('/catalog', [PublicProductController::class, 'index'])->name('catalog.index');
    Route::get('/catalog/{slug}', [PublicProductController::class, 'show'])->name('catalog.show');
});

Route::middleware(['web', 'auth:sanctum', 'role:admin'])
    ->prefix('admin/catalog')
    ->name('admin.catalog.')
    ->group(function () {
        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::resource('products', ProductController::class)->except(['show']);
        Route::resource('tags', TagController::class)->only(['index', 'store', 'destroy']);
    });
