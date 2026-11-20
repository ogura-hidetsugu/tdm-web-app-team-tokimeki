<?php

use App\Http\Controllers\FamilyController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ShoppingController;
use Illuminate\Support\Facades\Route;

// ホーム画面
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index']);

// 通知＆購入周期設定画面（ベルマーク・「通知設定を開く」のリンク先）
Route::prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/', [NotificationController::class, 'index'])->name('index');
    Route::post('/add/{itemId}', [NotificationController::class, 'addToList'])->name('addToList');
    Route::post('/settings', [NotificationController::class, 'updateSettings'])->name('updateSettings');
});

// 買い物リスト
Route::prefix('shopping')->name('shopping.')->group(function () {
    Route::get('/', [ShoppingController::class, 'index'])->name('index');
    Route::get('/create', [ShoppingController::class, 'create'])->name('create');
    Route::post('/', [ShoppingController::class, 'store'])->name('store');
    Route::post('/{id}/toggle', [ShoppingController::class, 'toggle'])->name('toggle');
    Route::delete('/{id}', [ShoppingController::class, 'destroy'])->name('destroy');
});

// おうちの在庫 & 商品登録（定期購入登録）
Route::prefix('items')->name('items.')->group(function () {
    Route::get('/', [ItemController::class, 'index'])->name('index');
    Route::get('/create', [ItemController::class, 'create'])->name('create');
    Route::post('/', [ItemController::class, 'store'])->name('store');
    Route::post('/{id}/stock', [ItemController::class, 'updateStock'])->name('updateStock');
    Route::delete('/{id}', [ItemController::class, 'destroy'])->name('destroy');
});

// 家族と共有
Route::prefix('family')->name('family.')->group(function () {
    Route::get('/', [FamilyController::class, 'index'])->name('index');
    Route::post('/members', [FamilyController::class, 'addMember'])->name('addMember');
    Route::post('/reaction', [FamilyController::class, 'postReaction'])->name('postReaction');
});
