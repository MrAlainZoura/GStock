<?php

use App\Http\Controllers\CategorieController;
use App\Http\Controllers\DepotController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('home');
});

Route::post('login',[UserController::class, 'login'])->name('login');
Route::post('logout',[UserController::class, 'logout'])->name('logout');
Route::get('dashbord',[HomeController::class, 'dashboard'])->name('dashboard');

Route::resource('depot', DepotController::class);
Route::resource('cat-pro', CategorieController::class);