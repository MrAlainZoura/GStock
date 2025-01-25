<?php

use App\Http\Controllers\ApprovisionnementController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\DepotController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AuthentifyMiddleware;

// Route::get('/', function () {
//     return view('home');
// });
Route::get('/',[HomeController::class, 'home'])->name('home');
Route::get('dashbord',[HomeController::class, 'dashboard'])->name('dashboard')->middleware(AuthentifyMiddleware::class);

Route::post('login',[UserController::class, 'login'])->name('login');
Route::post('logout',[UserController::class, 'logout'])->name('logout')->middleware(AuthentifyMiddleware::class);

Route::resource('user', UserController::class)->middleware(AuthentifyMiddleware::class);;
Route::get('approvisionnement/{depot}', [ApprovisionnementController::class,'showDepotAppro'])->name('aproDepot')->middleware( AuthentifyMiddleware::class);
Route::get('approvisionnement/{depot}/create', [ApprovisionnementController::class,'create'])->name('approCreate')->middleware( AuthentifyMiddleware::class);
Route::post('approvisionnement/{depot}/store', [ApprovisionnementController::class,'store'])->name('approStore')->middleware( AuthentifyMiddleware::class);
Route::post('approvisionnement/{appro}/confirmation', [ApprovisionnementController::class,'confirm'])->name('approConfirm')->middleware( AuthentifyMiddleware::class);
Route::get('approvisionnement/{appro}/show', [ApprovisionnementController::class,'show'])->name('approShow')->middleware( AuthentifyMiddleware::class);
Route::get('approvisionnement/{appro}/edit', [ApprovisionnementController::class,'edit'])->name('approEdit')->middleware( AuthentifyMiddleware::class);
Route::resource('depot', DepotController::class)->middleware(AuthentifyMiddleware::class);
Route::resource('cat-pro', CategorieController::class)->middleware(AuthentifyMiddleware::class);
Route::resource('prod', ProduitController::class)->middleware(AuthentifyMiddleware::class);
