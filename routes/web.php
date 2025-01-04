<?php

use App\Http\Controllers\CategorieController;
use App\Http\Controllers\DepotController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\UserController;

// Route::get('/', function () {
//     return view('home');
// });
Route::get('/',[HomeController::class, 'home'])->name('home');
Route::get('dashbord',[HomeController::class, 'dashboard'])->name('dashboard');

Route::post('login',[UserController::class, 'login'])->name('login');
Route::post('logout',[UserController::class, 'logout'])->name('logout');

Route::resource('depot', DepotController::class);
Route::resource('cat-pro', CategorieController::class);
Route::resource('prod', ProduitController::class);
Route::resource('produit', ProduitController::class);