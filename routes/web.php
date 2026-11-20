<?php

use App\Http\Controllers\ProductController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $productCount = Product::count();
    $lowStockProducts = Product::where('stock', '<=', 1)->orderBy('stock')->get();

    return view('home', [
        'productCount' => $productCount,
        'shoppingCount' => 3,
        'lowStockProducts' => $lowStockProducts,
    ]);
});

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
Route::post('/products', [ProductController::class, 'store'])->name('products.store');