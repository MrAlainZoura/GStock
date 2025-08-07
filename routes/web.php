<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DepotController;
use App\Http\Controllers\VenteController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\TransfertController;
use App\Http\Middleware\AuthentifyMiddleware;
use App\Http\Controllers\ApprovisionnementController;
use App\Http\Controllers\DeviseController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\RapportController;

// Route::get('/', function () {
//     return view('home');
// });
// Route::get('/',[HomeController::class, 'home'])->name('home');
Route::get('/',[UserController::class, 'loginCreate'])->name('home');
Route::get('dashbord',[HomeController::class, 'dashboard'])->name('dashboard')->middleware(AuthentifyMiddleware::class);

Route::get('sing_in',[UserController::class, 'loginCreate'])->name('loginCreate');
Route::post('nous_rejoindre',[UserController::class, 'createCompte'])->name('nousRejoindre');
Route::post('login',[UserController::class, 'login'])->name('login');
Route::post('logout',[UserController::class, 'logout'])->name('logout')->middleware(AuthentifyMiddleware::class);
Route::put('update-password-user/{user}',[UserController::class, 'updataPass'])->name('updataPass')->middleware(AuthentifyMiddleware::class);
Route::put('reset-password-user/{user}',[UserController::class, 'resetPass'])->name('resetPass')->middleware(AuthentifyMiddleware::class);

Route::resource('user', UserController::class)->middleware(AuthentifyMiddleware::class);;
Route::get('approvisionnement/{depot}', [ApprovisionnementController::class,'showDepotAppro'])->name('aproDepot')->middleware( AuthentifyMiddleware::class);
Route::get('approvisionnement/{depot}/create', [ApprovisionnementController::class,'create'])->name('approCreate')->middleware( AuthentifyMiddleware::class);
Route::post('approvisionnement/{depot}/store', [ApprovisionnementController::class,'store'])->name('approStore')->middleware( AuthentifyMiddleware::class);
Route::post('approvisionnement/{appro}/confirmation/{action}', [ApprovisionnementController::class,'confirm'])->name('approConfirm')->middleware( AuthentifyMiddleware::class);
Route::get('approvisionnement/{appro}/show', [ApprovisionnementController::class,'show'])->name('approShow')->middleware( AuthentifyMiddleware::class);
Route::get('approvisionnement/{appro}/edit', [ApprovisionnementController::class,'edit'])->name('approEdit')->middleware( AuthentifyMiddleware::class);

Route::get('transfert/{depot}', [TransfertController::class,'showDepotTrans'])->name('transDepot')->middleware( AuthentifyMiddleware::class);
Route::get('transfert/{depot}/create', [TransfertController::class,'create'])->name('transCreate')->middleware( AuthentifyMiddleware::class);
Route::post('transfert/{depot}/store', [TransfertController::class,'store'])->name('transStore')->middleware( AuthentifyMiddleware::class);
Route::post('transfert/{transfert}/confirmation', [TransfertController::class,'confirm'])->name('transConfirm')->middleware( AuthentifyMiddleware::class);
Route::get('transfert/{transfert}/show', [TransfertController::class,'show'])->name('transShow')->middleware( AuthentifyMiddleware::class);
Route::get('transfert/{transfert}/edit', [TransfertController::class,'edit'])->name('transEdit')->middleware( AuthentifyMiddleware::class);
Route::delete('transfert/{transfert}/delete', [TransfertController::class,'destroy'])->name('transDelete')->middleware( AuthentifyMiddleware::class);

Route::get('vente/{depot}', [VenteController::class,'showDepotVente'])->name('venteDepot')->middleware( AuthentifyMiddleware::class);
Route::get('vente/{depot}/create', [VenteController::class,'create'])->name('venteCreate')->middleware( AuthentifyMiddleware::class);
Route::post('vente/{depot}/store', [VenteController::class,'store'])->name('venteStore')->middleware( AuthentifyMiddleware::class);
Route::get('vente/{vente}/show', [VenteController::class,'show'])->name('venteShow')->middleware( AuthentifyMiddleware::class);
Route::get('vente/{vente}/edit', [VenteController::class,'edit'])->name('venteEdit')->middleware( AuthentifyMiddleware::class);
Route::delete('vente/{vente}/delete', [VenteController::class,'destroy'])->name('venteDelete')->middleware( AuthentifyMiddleware::class);

Route::get('rapport/{depot}/journalier', [RapportController::class,'journalier'])->name('rapport.jour')->middleware( AuthentifyMiddleware::class);
Route::get('rapport/{depot}/mensuel', [RapportController::class,'mensuel'])->name('rapport.mois')->middleware( AuthentifyMiddleware::class);
Route::get('rapport/{depot}/annuel', [RapportController::class,'annuel'])->name('rapport.annee')->middleware( AuthentifyMiddleware::class);
Route::get('rapport/{depot}/voir-plus/{action}', [RapportController::class,'seemore'])->name('rapport.more')->middleware( AuthentifyMiddleware::class);
Route::get('rapport/{vente}/facture/{action}', [RapportController::class,'facture'])->name('facturePDF')->middleware( AuthentifyMiddleware::class);
Route::get('rapport/{depot}/mail', [RapportController::class,'sendMailrapport'])->name('sendMailrapport')->middleware( AuthentifyMiddleware::class);

Route::get('{depot}/parametre', [DepotController::class, 'depotSetting'])->name("depotSetting")->middleware(AuthentifyMiddleware::class);
Route::get('{depot}/produits', [DepotController::class, 'showProduit'])->name("showProduit")->middleware(AuthentifyMiddleware::class);

Route::put('{depot}/{devise}/update', [DeviseController::class, 'update'])->name("devise.update")->middleware(AuthentifyMiddleware::class);

Route::post('import_produit',[ ProduitController::class, 'importProduitExcel'])->name('import_prod_excel')->middleware(AuthentifyMiddleware::class);
Route::get('vente/creances/{depot}', [PaiementController::class,'creance'])->name('creanceDepot')->middleware( AuthentifyMiddleware::class);
Route::post('vente/creances/{vente}', [PaiementController::class,'store'])->name('creanceStore')->middleware( AuthentifyMiddleware::class);
Route::resource('paiement',PaiementController::class)->middleware(AuthentifyMiddleware::class);
Route::resource('depot', DepotController::class)->middleware(AuthentifyMiddleware::class);
Route::resource('cat-pro', CategorieController::class)->middleware(AuthentifyMiddleware::class);
Route::resource('prod', ProduitController::class)->middleware(AuthentifyMiddleware::class);
