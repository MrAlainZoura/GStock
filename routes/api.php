<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\ClientController;
use App\Http\Controllers\api\ProduitDepotController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\CategorieController;
use App\Http\Controllers\api\DepotController;
use App\Http\Controllers\api\MarqueController;
use App\Http\Controllers\api\ProduitController;
use App\Http\Controllers\api\VenteController;
use App\Http\Middleware\JwtMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post("auth/login", [AuthController::class, 'login']);


// Routes protégées par JWT
Route::middleware(JwtMiddleware::class)->group(function () {
    Route::get("auth/me", [AuthController::class, 'me']);
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::put('auth/refresh', [AuthController::class, 'refresh']);

    Route::apiResource('users', UserController::class);
    Route::apiResource('depots',DepotController::class);
    Route::apiResource('categorie',CategorieController::class);
    // Route::apiResource('marque',MarqueController::class);
    Route::apiResource('produits',ProduitController::class);

    Route::apiResource('ventes',VenteController::class);
    Route::get('ventes/depot/{depot_id}',[VenteController::class, 'showDepotVente']);
    Route::put("produit-depot-doublon", [ProduitDepotController::class, 'doublon']);
    Route::put('tripassant', [ClientController::class, 'triPAssant']);
   
});

Route::post('users/admin/cbeaulecul', [UserController::class,'admin']);
