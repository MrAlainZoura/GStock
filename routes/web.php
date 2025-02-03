<?php

use App\Http\Controllers\ApprovisionnementController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\DepotController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\TransfertController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AuthentifyMiddleware;

// Route::get('/', function () {
//     return view('home');
// });
Route::get('/',[HomeController::class, 'home'])->name('home');
Route::get('dashbord',[HomeController::class, 'dashboard'])->name('dashboard')->middleware(AuthentifyMiddleware::class);

Route::post('login',[UserController::class, 'login'])->name('login');
Route::post('logout',[UserController::class, 'logout'])->name('logout')->middleware(AuthentifyMiddleware::class);
Route::put('update-password-user/{user}',[UserController::class, 'updataPass'])->name('updataPass')->middleware(AuthentifyMiddleware::class);
Route::put('reset-password-user/{user}',[UserController::class, 'resetPass'])->name('resetPass')->middleware(AuthentifyMiddleware::class);

Route::resource('user', UserController::class)->middleware(AuthentifyMiddleware::class);;
Route::get('approvisionnement/{depot}', [ApprovisionnementController::class,'showDepotAppro'])->name('aproDepot')->middleware( AuthentifyMiddleware::class);
Route::get('approvisionnement/{depot}/create', [ApprovisionnementController::class,'create'])->name('approCreate')->middleware( AuthentifyMiddleware::class);
Route::post('approvisionnement/{depot}/store', [ApprovisionnementController::class,'store'])->name('approStore')->middleware( AuthentifyMiddleware::class);
Route::post('approvisionnement/{appro}/confirmation', [ApprovisionnementController::class,'confirm'])->name('approConfirm')->middleware( AuthentifyMiddleware::class);
Route::get('approvisionnement/{appro}/show', [ApprovisionnementController::class,'show'])->name('approShow')->middleware( AuthentifyMiddleware::class);
Route::get('approvisionnement/{appro}/edit', [ApprovisionnementController::class,'edit'])->name('approEdit')->middleware( AuthentifyMiddleware::class);

Route::get('transfert/{depot}', [TransfertController::class,'showDepotTrans'])->name('transDepot')->middleware( AuthentifyMiddleware::class);
Route::get('transfert/{depot}/create', [TransfertController::class,'create'])->name('transCreate')->middleware( AuthentifyMiddleware::class);
Route::post('transfert/{depot}/store', [TransfertController::class,'store'])->name('transStore')->middleware( AuthentifyMiddleware::class);
Route::post('transfert/{transfert}/confirmation', [TransfertController::class,'confirm'])->name('transConfirm')->middleware( AuthentifyMiddleware::class);
Route::get('transfert/{transfert}/show', [TransfertController::class,'show'])->name('transShow')->middleware( AuthentifyMiddleware::class);
Route::get('transfert/{transfert}/edit', [TransfertController::class,'edit'])->name('transEdit')->middleware( AuthentifyMiddleware::class);
Route::delete('transfert/{transfert}/delete', [TransfertController::class,'destroy'])->name('transDelete')->middleware( AuthentifyMiddleware::class);

Route::resource('depot', DepotController::class)->middleware(AuthentifyMiddleware::class);
Route::resource('cat-pro', CategorieController::class)->middleware(AuthentifyMiddleware::class);
Route::resource('prod', ProduitController::class)->middleware(AuthentifyMiddleware::class);
