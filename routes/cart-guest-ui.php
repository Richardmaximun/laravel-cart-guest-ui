<?php

use Illuminate\Support\Facades\Route;
use Richardmaximun\CartGuestUi\Http\Controllers\BadgeController;
use Richardmaximun\CartGuestUi\Http\Controllers\CartController;

$mw = config('cart-guest-ui.routes.middleware', ['web']);
$prefix = config('cart-guest-ui.routes.prefix', '');

Route::group(['prefix' => $prefix, 'middleware' => $mw], function () {
    Route::get('/cart/badge', [BadgeController::class, 'show'])->name('cart.badge');

    Route::get('/cart', [CartController::class, 'index'])->name(config('cart-guest-ui.routes.names.index'));
    Route::post('/cart/items', [CartController::class, 'store'])->name(config('cart-guest-ui.routes.names.store'));
    Route::patch('/cart/items/{type}/{id}', [CartController::class, 'update'])->name(config('cart-guest-ui.routes.names.update'));
    Route::delete('/cart/items/{type}/{id}', [CartController::class, 'destroy'])->name(config('cart-guest-ui.routes.names.destroy'));
});