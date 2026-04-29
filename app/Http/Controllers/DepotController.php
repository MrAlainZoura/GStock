<?php

namespace App\Http\Controllers;

use App\Enum\DepotType;
use App\Models\Approvisionnement;
use App\Models\Categorie;
use App\Models\Depot;
use App\Models\Devise;
use App\Models\Produit;
use App\Models\ProduitDepot;
use App\Models\Reservation;
use App\Models\Transfert;
use App\Models\User;
use App\Models\Vente;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DepotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $depot = Depot::latest()->get();
        return response()->json(['success'=>true, 'data'=>$depot]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $validateDate = Validator::make($request->all(),
        [
            'libele'=>'required|string|max:255',
            'type'=>'required|string|max:255',
            'monnaie'=>'required|string|max:255',
            'taux'=>'required|max:255',
        ]);

        if($validateDate->fails()){
            return $validateDate->errors();
        }
        $data = [
            'libele'=>$request->libele,
            'type'=>$request->type,
            'user_id'=>Auth::user()->id
        ];
        // dd($data, $dataDevise);
        $depot = Depot::create($data);
        if($depot){
            $dataDevise = [
                'libele'=>$request->monnaie, 
                'taux'=>$request->taux, 
                'depot_id'=>$depot->id
            ];
            $createDevise = Devise::firstOrCreate($dataDevise);
        }
        return back()->with('success','Depot ajouté avec success');
    }

    /**
     * Display the specified resource.
     */
    public function show($getDepot)
    {
        if(!is_numeric($getDepot)){
            $depot = Depot::where("libele",$getDepot)->first();
        }else{
            $getDepot = $getDepot/12726654;
            $depot = Depot::where("id",$getDepot)->first();
        }

        if(!$depot){
            return to_route('dashboard')->with('echec',"Choisissez un dépôt pour effectuer vos opérations!"); 
        }
        /**
         * mise à jour de prix en cdf et devise dans depotproduit de produit en fonction de depot
         * prix de vente precedente en cdf et ajout reference devise,
         * meme chose pour reservation
         */
        $taux = $depot->devise()->first()->taux;
        $depotId=$depot->id;
        $checkJourUpdate = $this->jours(Carbon::now());
        // $checkJourUpdate = $this->jours(Carbon::parse("2026-03-29"));
        $getStatusProdUpdate = self::updatePrixProduit($depotId, $taux);
        // if( $checkJourUpdate != null){
        //     if($depot->day_update == null){
        //         // $getStatusVenteUpdate = self::updateAllVente($depotId, $checkJourUpdate);
        //         // $getStatusReservationUpdate = self::updateAllReservation($depotId,$checkJourUpdate);
        //         $getStatusProdUpdate = self::updatePrixProduit($depotId, $taux);
        //         // $depot->day_update = Carbon::now();
        //         // $depot->save();
        //         // dd('premiere fois', $this->jours(Carbon::now()));
        //     }else{
        //         // dd('on TEST LES DATES');
        //         if(Carbon::parse( $depot->day_update)->format('Y-m-d') != $checkJourUpdate){
        //             // dd('on lance', $checkJourUpdate);
        //             $getStatusVenteUpdate = self::updateAllVente($depotId, $checkJourUpdate);
        //             $depot->day_update = $checkJourUpdate;
        //             $depot->save();
        //             dd("on vient de faire la maj auj");
        //         }
        //     }
        // }
        // dd("Cest fait aujourdi", $checkJourUpdate, $depot);

        session(['depot' => $depot->libele]);
        session(['depot_id' => $depot->id]);
        $user = Auth::user();
        $cat= Categorie::all();
        $mois = Carbon::now()->format('m');

        $vendeurs = Vente::selectRaw('user_id, COUNT(*) as count')
                    ->groupBy('user_id')
                    ->orderByDesc('count')
                    ->whereMonth('created_at', (int)$mois)
                    ->where('depot_id', $depot->id)
                    ->take(2)
                    ->get();
        $approMois1 = Approvisionnement::whereMonth('created_at', (int)$mois)
                    ->where('depot_id', $depot->id)
                    ->get();
        $transMois1 = Transfert::whereMonth('created_at', (int)$mois)
                    ->where('depot_id', $depot->id)
                    ->get();
        $totalVenteMois = Vente::where('depot_id', $depot->id)->whereMonth('created_at',$mois)->get();
        
        $tabProdVendu = [];
        foreach($totalVenteMois as $key=>$value){
            foreach($value->venteProduit as $k=>$v){
                if(array_key_exists($v->produit->marque->categorie->libele." ".$v->produit->marque->libele." ".$v->produit->libele , $tabProdVendu)){
                    $newQt = $tabProdVendu[$v->produit->marque->categorie->libele." ".$v->produit->marque->libele." ".$v->produit->libele] +$v->quantite;
                    $tabProdVendu[$v->produit->marque->categorie->libele." ".$v->produit->marque->libele." ".$v->produit->libele] = $newQt;
                }else{
                    $tabProdVendu[$v->produit->marque->categorie->libele." ".$v->produit->marque->libele." ".$v->produit->libele]=$v->quantite;
                }

            }
        }
        $totalApro = 0;
        foreach($approMois1 as $cl=>$vl){
            $totalApro+=$vl->quantite;
        }

        $totalTrans = 0;
        foreach($transMois1 as $cle=>$valeur){
            foreach($valeur->produitTransfert as $c=>$vl){
                $totalTrans+=$vl->quantite;
            }
        }
        arsort($tabProdVendu);
        $nombreVentes = count($totalVenteMois);
        $affichage = ($nombreVentes > 9) ? $nombreVentes : "0" . $nombreVentes;

        // (count($totalVenteMois)>9)?count($totalVenteMois):$totalVenteMois="0".count($totalVenteMois);
        (count($approMois1)>9)?$approMois=count($approMois1):$approMois="0".count($approMois1);
        (count($transMois1)>9)?$transMois=count($transMois1):$transMois="0". count($transMois1);
        
        $depot->totalVente = $affichage;
        $depot->totalApro = $totalApro;
        $depot->totalTrans = $totalTrans;
        $depot->approMois = $approMois;
        $depot->transMois = $transMois;
        $prodDepot = ProduitDepot::where("depot_id",$depot->id)->with('produit')->get();
        return view('depot.show',compact('prodDepot','depot','user','vendeurs','tabProdVendu'));
    }
    public function showProduit(string $depot, $id)
    {
        $id = $id/12;
        $depotData = Depot::where("libele",$depot)->where('id', $id)->first();
        if(!$depotData){
            return redirect()->back()->with('echec','Renseignement incorrect, recommencer');
        }
        $user = Auth::user();
        $cat = Categorie::orderBy('libele')->with('marque')->get();        
        $prodDepot = ProduitDepot::where("depot_id",$depotData->id)->with('produit.marque')->latest()->get();
        return view('depot.produit',compact('prodDepot','depotData','user','cat'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($depot)
    {
        // dd($depot);
        $getDepotInformation = Depot::where('libele', $depot)->where('id', session('depot_id'))->first();
        if($getDepotInformation != null){
            $depotType = DepotType::cases();
            return view('depot.update',compact("getDepotInformation","depotType"));
        }
        return back()->with('echec',"Une erreur inattendue s'est produite");

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $depot)
    {
        $dossier ='logo';
        if (!Storage::disk('direct_public')->exists($dossier)) {
            Storage::disk('direct_public')->makeDirectory($dossier);
        }
        // dd($request->all());
        $validateDate = Validator::make($request->all(),
        [
            'id'=>'required',
            'libele'=>'required|string|max:255',
        ]);

        if($validateDate->fails()){
            // return $validateDate->errors();
        }
        $fichier = $request->file('logo');
        $type = ($fichier!=null)?$fichier->getClientOriginalExtension():null;
        
        $data = [
            'libele'=>$request->libele,
            'logo'=>($request->file('logo')!=null)? "$request->libele.$type":null,
            'email'=>$request->email,
            'contact1'=>$request->numPrincipal,
            'contact'=>$request->numSecond,
            'cpostal'=>$request->codeP,
            'pays'=>$request->pays,
            'province'=>$request->province,
            'ville'=>$request->ville,
            'avenue'=>$request->avenue,
            'idNational'=>$request->idNat,
            'numImpot'=>$request->impot,
            'autres'=>$request->autres,
            'remboursement_delay'=>$request->remboursement_delay,
            'type'=>$request->type
        ];
        $data = array_filter($data, function($val){return !is_null($val);});

        // $depotUpdate = Depot::where('id',session('depot_id'))->update($data);

        $depotUpdate = Depot::where('id',session('depot_id'))->first();
        $nomFichier = $depotUpdate->logo;
        // dd($nomFichier);
        
        if($depotUpdate->update($data)){
            if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
                $cheminFichier = $dossier . '/' . $nomFichier;
                $getDepot = Depot::find( $depotUpdate->id); 
                $nouveauNom = $getDepot ? $getDepot->logo : null;

                if ($nouveauNom !== null && Storage::disk('direct_public')->exists($cheminFichier)) {
                    Storage::disk('direct_public')->move($cheminFichier, $dossier . '/' . $nouveauNom);
                }
                // Enregistrer le nouveau fichier avec le nom d'origine
                $fichier = $request->file('logo')->storeAs(
                    $dossier,
                    $nouveauNom,
                    'direct_public'
                );
            }
       }

        $dataDevise[] = ["libele"=> $request->monnaie,'taux'=>$request->tauxDEchange];
        $dataDevise[] = ["libele"=> $request->monnaie1,'taux'=>$request->tauxDEchange1];
        
        $dataDevise = array_filter($dataDevise, function($val){return !is_null($val);});
        if($dataDevise != null){
           foreach ($dataDevise as $devise) {
                $libele = trim(strtolower($devise['libele'] ?? ''));
                $taux = (float)($devise['taux'] ?? null);

                if (empty($libele) || !is_numeric($taux)) {
                    continue; 
                }

                $taux = number_format((float) $taux, 2, '.', '');
                
                $createDevise = Devise::firstOrCreate([
                    'depot_id' => $depotUpdate->id,
                    'libele' => $libele,
                    'taux' => $taux,
                ]);
            }
        }
        return back()->with('success','Information mise à jour avec success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, Request $request)
    {
        $libele = $request->libele;
        $id = $id/13;
        $delete = Depot::where('id',$id)->where('libele', $libele)->first();
        if($delete){
           // $delete->delete();
            return to_route('dashboard')->with('success','Bientot disponible');
        }
        return back()->with('echec','Echec depot introuvable');

    }

    public function depotSetting(string $depot){
         $depotData = Depot::with('depotUser')->where("libele",$depot)->where('id', session('depot_id'))->first();
                 
         $collaborateur = $depotData?->depotUser?->count() ?? 0;
         $tabCatMark = [];
         $countMark = 0;

         foreach($depotData->produitDepot as $k=>$v){

            if (array_key_exists($v->produit->marque->categorie->libele, $tabCatMark)) {
                if (!in_array($v->produit->marque->libele, $tabCatMark[$v->produit->marque->categorie->libele])) {
                    $tabCatMark[$v->produit->marque->categorie->libele][] = $v->produit->marque->libele;
                    $countMark+=1;
                } 
            } else {
                $tabCatMark[$v->produit->marque->categorie->libele] = [$v->produit->marque->libele];
                $countMark+=1;
            }
         }
        return view('depot.setting',compact('depotData', 'collaborateur','tabCatMark','countMark'));
    }

    public function geolocalisation(Request $request, $depot, $action){
        $getDepot = Depot::find($depot);
        
       if(!$getDepot){
            return back()->with('echec',"Renseignement invalide");
       }
        if($action == 'auto'){
            // $positionAuto = self::getPosition($request->ip());
            // if(!$positionAuto['ok']){
            //   return back()->with('echec',$positionAuto['error'] );
            // }
            if (!is_null($request->lonAuto) && is_numeric($request->lonAuto) &&
                !is_null($request->latAuto) && is_numeric($request->latAuto)){

                $getDepot->update(['lat'=>$request->latAuto, 'lon'=>$request->lonAuto]);
                return back()->with('success', "Position mise à jour par détection automatique");
            }
        }
        if($action == 'insert'){
            if (!is_null($request->lonM) && is_numeric($request->lonM) &&
                !is_null($request->latM) && is_numeric($request->latM)) {
                
                $getDepot->update(['lat'=>$request->latM, 'lon'=>$request->lonM]);
                return back()->with('success', "Position mise à jour");
            }
            return back()->with('echec', 'Coordonnées invalides');
        }
        return back()->with('echec', 'Action invalide');
    }
    static public function getPosition(string $requestIp=''):array{
        try{
             $ipResponse = Http::timeout(10)->get('https://api.ipify.org');
            $ip = ($requestIp == null)? $ipResponse->body() : $requestIp;

            // Géolocalisation
            $url = "http://ip-api.com/json/{$ip}";
            $positionResponse = Http::timeout(10)->get($url);

            if ($positionResponse->failed()) {
                return [
                    "ok"=>false,
                   "error"=> "Position automatique a échoué"
                ];
            }
            $localisation = $positionResponse->json();
           return [
                    'lon'=>$localisation['lon'],
                    'lat'=>$localisation['lat'],
                    'ip'=>$localisation['query'],
                    'city'=>$localisation['city'],
                    'ok'=>true
                ];
        }catch(Exception $e){
            return [
                    "ok"=>false,
                    "error"=> "!Position automatique a échoué"
                ];
        }
    }


    /*static public function updateAllVente($depotId, $monthYear){
        $mois = Carbon::parse($monthYear)->format('m');
        $year ="20".Carbon::parse($monthYear)->format('y');
        $allVente = Vente::with('venteProduit','paiement')
                    ->withTrashed()
                    ->where('depot_id',$depotId)
                    ->whereMonth('created_at',$mois)
                    ->whereYear('created_at', $year)
                    ->get();
                // dd($allVente, $mois, $year);

        if($allVente){
            foreach ($allVente as $vente) {
                    $tauxVente = $vente->updateTaux;
                    // Update paiements
                    $vente->paiement->each(function ($paiementV) use ($tauxVente) {
                        $paiementV->reference_devise = $paiementV->net;
                        $paiementV->avance = (float)$paiementV->avance * (float)$tauxVente;
                        $paiementV->solde  = (float)$paiementV->solde * (float)$tauxVente;
                        $paiementV->net   = (float)$paiementV->net * (float)$tauxVente;
                        $paiementV->save();
                    });
    
                    // Update produits
                    $vente->venteProduit->each(function ($prodVendu) use ($tauxVente) {
                        $prodVendu->prixT = (float)$prodVendu->prixT * (float)$tauxVente;
                        $prodVendu->prixU = (float)$prodVendu->prixU * (float)$tauxVente;
                        $prodVendu->save();
                    });
    
            } 
        }
        // dd($allVente);
        return true;
    }*/

        static public function updateAllVente($depotId, $monthYear)
        {
            $mois = Carbon::parse($monthYear)->format('m');
            $year = "20".Carbon::parse($monthYear)->format('y');

            $ventes = Vente::withTrashed()
                ->where('depot_id', $depotId)
                ->whereMonth('created_at', $mois)
                ->whereYear('created_at', $year)
                ->limit(500)
                ->get(['id', 'updateTaux']);

           foreach ($ventes as $vente) {
                $taux = $vente->updateTaux;

                DB::transaction(function () use ($vente, $taux) {
                    // Update paiements
                    DB::table('paiements')
                        ->where('vente_id', $vente->id)
                        ->whereNull('reference_devise')
                        ->update([
                            'reference_devise' => DB::raw('net'),
                            'avance' => DB::raw('avance * '.$taux),
                            'solde'  => DB::raw('solde * '.$taux),
                            'net'    => DB::raw('net * '.$taux),
                        ]);

                    // Update produits
                    DB::table('vente_produits')
                        ->where('vente_id', $vente->id)
                        ->update([
                            'prixT' => DB::raw('prixT * '.$taux),
                            'prixU' => DB::raw('prixU * '.$taux),
                        ]);
                });
            }
            return true;
        }
        // static public function updateAllVente($depotId, $monthYear)
        // {
        //     $mois = Carbon::parse($monthYear)->format('m');
        //     $year = "20".Carbon::parse($monthYear)->format('y');

        //     Vente::with(['venteProduit','paiement' => function($q) {
        //             $q->whereNull('reference_devise'); // ne prendre que ceux non encore mis à jour
        //         }])
        //         ->withTrashed()
        //         ->where('depot_id', $depotId)
        //         ->whereMonth('created_at', $mois)
        //         ->whereYear('created_at', $year)
        //         ->chunk(200, function ($ventes) {
        //             foreach ($ventes as $vente) {
        //                 $tauxVente = $vente->updateTaux;

        //                 // Update paiements uniquement si reference_devise est null
        //                 $vente->paiement->each(function ($paiementV) use ($tauxVente) {
        //                     $paiementV->reference_devise = $paiementV->net;
        //                     $paiementV->avance = (float)$paiementV->avance * (float)$tauxVente;
        //                     $paiementV->solde  = (float)$paiementV->solde * (float)$tauxVente;
        //                     $paiementV->net    = (float)$paiementV->net * (float)$tauxVente;
        //                     $paiementV->save();
        //                 });

        //                 // Update produits
        //                 $vente->venteProduit->each(function ($prodVendu) use ($tauxVente) {
        //                     $prodVendu->prixT = (float)$prodVendu->prixT * (float)$tauxVente;
        //                     $prodVendu->prixU = (float)$prodVendu->prixU * (float)$tauxVente;
        //                     $prodVendu->save();
        //                 });
        //             }
        //         });

        //     return true;
        // }

        static public function updateAllReservation($depotId, $monthYear)
        {
            $mois = Carbon::parse($monthYear)->format('m');
            $year = "20".Carbon::parse($monthYear)->format('y');

            // Récupérer les réservations concernées
            $reservations = Reservation::withTrashed()
                ->where('depot_id', $depotId)
                ->whereMonth('created_at', $mois)
                ->whereYear('created_at', $year)
                ->get(['id', 'updateTaux']); // seulement les colonnes utiles

            foreach ($reservations as $reservation) {
                $taux = $reservation->updateTaux;

                // Update paiements en masse (uniquement ceux non traités)
                DB::table('paiements')
                    ->where('reservation_id', $reservation->id)
                    ->whereNull('reference_devise')
                    ->update([
                        'reference_devise' => DB::raw('net'),
                        'avance' => DB::raw('avance * '.$taux),
                        'solde'  => DB::raw('solde * '.$taux),
                        'net'    => DB::raw('net * '.$taux),
                    ]);

                // Update produits en masse
                DB::table('reservation_produits')
                    ->where('reservation_id', $reservation->id)
                    ->update([
                        'montant' => DB::raw('montant * '.$taux),
                    ]);
            }

            return true;
        }
    /*static public function updateAllReservation($depotId, $monthYear){
        $mois = Carbon::parse($monthYear)->format('m');
        $year ="20".Carbon::parse($monthYear)->format('y');
        $allVente = Reservation::with('reservationProduit','paiement')
                    ->withTrashed()
                    ->where('depot_id',$depotId)
                    ->whereMonth('created_at',$mois)
                    ->whereYear('created_at', $year)
                    ->get();
        // return true;
        // dd($allVente, $mois, $year);
        if($allVente){
            foreach ($allVente as $vente) {
                    $tauxVente = $vente->updateTaux;
                    // Update paiements
                    $vente->paiement->each(function ($paiementV) use ($tauxVente) {
                        $paiementV->reference_devise = $paiementV->net;
                        $paiementV->avance = (float)$paiementV->avance * (float)$tauxVente;
                        $paiementV->solde  = (float)$paiementV->solde * (float)$tauxVente;
                        $paiementV->net   = (float)$paiementV->net * (float)$tauxVente;
                        $paiementV->save();
                    });
                    // Update produits
                    $vente->reservationProduit->each(function ($prodVendu) use ($tauxVente) {
                        $prodVendu->montant = (float)$prodVendu->montant * (float)$tauxVente;
                        $prodVendu->save();
                    });
    
            } 
        }
        // dd($allVente);
        return true;
    }*/

        static public function updatePrixProduit($depotId, $taux)
        {
            Produit::whereHas('produitDepot', function($q) use($depotId) {
                    $q->where('depot_id', $depotId);
                })
                ->chunk(200, function ($produits) use ($depotId, $taux) {
                    foreach ($produits as $produit) {
                        $depotProd = $produit->produitDepot()
                            ->where('depot_id', $depotId)
                            ->whereNull('cdf_prix') // éviter de retraiter
                            ->where('produit_id', $produit->id)
                            ->first();

                        if ($depotProd) {
                            $depotProd->update([
                                'cdf_prix'    => (float)$produit->prix * (float)$taux,
                                'devise_prix' => (float)$produit->prix,
                            ]);
                        }
                    }
                });

            return true;
        }
    // static public function updatePrixProduit($depotId, $taux){
    //      $produits = Produit::with('produitDepot')
    //     ->whereHas('produitDepot', function($q) use($depotId) {
    //         $q->where('depot_id', $depotId);
    //         })
    //         ->get();
        
    //     if($produits) {
    //         foreach ($produits as $produit) {
    //             $depotProd = $produit->produitDepot()
    //                         ->where('depot_id', $depotId)
    //                         ->where('cdf_prix', null)
    //                         ->where('produit_id', $produit->id)
    //                         ->first();
                  
    //             if($depotProd && $depotProd->count() > 0){
    //                 $depotProd->update([
    //                     'cdf_prix'    => (float)$produit->prix * (float)$taux,
    //                     'devise_prix' => (float)$produit->prix,
    //                 ]);
    //             }
    //         }
    //     }  
    //     return true;
    // }

    public function jours ($today){
        $start = Carbon::parse('2026-03-24'); // aujourd'hui : 23/03/2026
        $monthsBack = 9;        // nombre de mois à générer
        $table = [];
    

        for ($i = 0; $i < $monthsBack; $i++) {
            $day   = $start->copy()->addDays($i)->format('Y-m-d'); // chaque jour +i
            $value = $start->copy()->subMonths($i)->format('Y-m-d'); // mois correspondant -i
            $table[$day] = $value;
        }
        // Vérifier si aujourd'hui est dans le tableau
        $todayKey = $today->format('Y-m-d');
        if (array_key_exists($todayKey, $table)) {
            $currentDate = $table[$todayKey];
            return $currentDate;
        }
        // dd($table);
        return null;
    }
}
