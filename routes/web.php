<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\ShopController;

Route::get('/', [MainController::class, 'index']);

Route::group(['middleware' => ['auth:user']], function() {

  Route::get('/mycart', [MainController::class, 'myCart']);
  Route::post('/mycart', [MainController::class, 'addMyCart']);
  Route::post('/delcart', [MainController::class, 'deleteCart']);
  Route::post('/checkout', [MainController::class, 'checkout']);
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
