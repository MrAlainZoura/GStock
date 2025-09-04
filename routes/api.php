<?php

use App\Http\Controllers\api\ClientController;
use App\Http\Controllers\api\ProduitDepotController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\DepotController;
use App\Http\Controllers\MarqueController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route::apiResource('users',UserController::class);
Route::post('users/admin/cbeaulecul', [UserController::class,'admin']);
Route::apiResource('users', UserController::class);
Route::apiResource('depots',DepotController::class);
Route::apiResource('categorie',CategorieController::class);
Route::apiResource('marque',MarqueController::class);
Route::apiResource('produits',ProduitController::class);
Route::put("produit-depot-doublon", [ProduitDepotController::class, 'doublon']);
Route::put('tripassant', [ClientController::class, 'triPAssant']);