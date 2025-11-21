<?php

namespace App\Http\Controllers;

use App\Models\Depot;
use App\Models\Vente;
use App\Models\Client;
use App\Models\Paiement;
use App\Models\Categorie;
use App\Models\ProduitDepot;
use Illuminate\Http\Request;
use App\Models\Compassassion;
use Illuminate\Support\Carbon;

class CompassassionController extends Controller
{
    public function index()
    {
        return Compassassion::all();
    }
    public function show($libel,$id){
        $depot = Depot::find($id);
      
        if(!$depot){
            return to_route('dashboard');
        }
        $compassassionS = Vente::whereHas('compassassion')
            ->with(['paiement', 'venteProduit', 'compassassion'])
            ->where('depot_id', $depot->id)
            ->latest()
            ->get();

        // Tri par date de création de la relation compassassion
        $compassassion = $compassassionS->sortByDesc(function ($vente) {
            return optional($vente->compassassion->max('created_at'));
        });
        return view('compassassion.index', compact('compassassion','depot'));
    }
    public function create($depot ,$vente_id){
        $vente= Vente::find($vente_id);
        if($vente){
            $depot = $vente->depot;
            $paiement = collect($vente->paiement)->sum('net');            ;
            $produit = ProduitDepot::where("depot_id",$depot->id)->with("produit.marque","produit.marque.categorie")->get();
            return view("compassassion.create", compact("depot","paiement","produit", 'vente'));
        }
        return back()->with('echec', "Demande mal formulée, veuillez réessayez !");
    }

    public function store(Request $request){
        // dd($request->all(), Compassassion::all());
        $vente = Vente::find($request->vente_id);
        if($vente){
            if($vente->compassassion->count() > 0){
                return back()->with('echec', "Echec, Vous avez déjà effectué une compassassion pour cette vente");
            }
            // dd($vente->paiement);
            $ancVenteIdQt = [];
            $stockUpdate = [];
            foreach($vente->venteproduit as $c=>$vl){
                $ancVenteIdQt[$vl->produit->id]=$vl->quantite;
                $stockUpdate[$vl->produit->id]=$vl->quantite;
                // dd($vl);
            }
            $tailleNewInput= count($request->produits);
            $sizeOldInput = count($ancVenteIdQt);
            $sameId = 0;
            $needTrait =[];
            $onlyNewProduct =[];
            $newPaiement = 0;
            $oldPaiement = ($vente->paiement->count() >0)? $vente->paiement[0]->net:null;
            // dd($ancVenteIdQt, $oldPaiement);
            $tabDataVenteProduit = [];
            foreach($request->produits as $key=>$val){
                $verifQTe = ProduitDepot::where('produit_id',$key)->where('depot_id',$vente->depot_id)->first();
                //    dd($verifQTe);
                $getQtNow = $verifQTe->quantite;
                foreach($val as $k=>$v){
                    $newPaiement +=(float)$v;
                    if (array_key_exists($key, $ancVenteIdQt)) {
                        // echo "La clé $key existe."; on verifie la qt et la taille de deux tableaux
                        if($ancVenteIdQt[$key] > $k){
                            //stoc++ retour au shop
                            $needTrait[$key] =  $ancVenteIdQt[$key] - $k + $getQtNow;
                            $dataSave['id']=$key;
                            $dataSave['qt']=$k;
                            $dataSave['pt']=$v;
                            array_push($tabDataVenteProduit,$dataSave);   
                            unset($stockUpdate[$key]);
                        }else{
                            //stoc-- sortie
                            $updateStockNew = $ancVenteIdQt[$key] - $k + $getQtNow;
                            // dd('comparons', $updateStockNew);
                            if($ancVenteIdQt[$key] == $k){
                                $dataSave['id']=$key;
                                $dataSave['qt']=$k;
                                $dataSave['pt']=$v;
                                array_push($tabDataVenteProduit,$dataSave); 
                                unset($stockUpdate[$key]);
                                $needTrait[$key] = $getQtNow;

                                // dd($ancVenteIdQt, $k, "qte egale");
                            }elseif(0 < $updateStockNew){
                                // dd('superio à 0');
                                  $dataSave['id']=$key;
                                $dataSave['qt']=$k;
                                $dataSave['pt']=$v;
                                array_push($tabDataVenteProduit,$dataSave);
                                unset($stockUpdate[$key]); 
                                $needTrait[$key] =  $updateStockNew;
                                
                            }else{
                                $produitInsuffisant = $verifQTe->produit->libele ." ".$updateStockNew*(-1) ."pc en moins"; 
                                // dd($produitInsuffisant, $getQtNow,$k, $ancVenteIdQt[$key] );
                                return back()->with('echec',"Stock insufisant pour le produit $produitInsuffisant pour effectuer cette opératoin, approvisionner puis réessayer!");
                            }
                        }
                        // $stockUpdate[$key] = $ancVenteIdQt[$key] - $k;
                        $sameId++;
                    }else{

                        $newQt = $getQtNow - $k;
                        if($newQt <= 0 ){
                            $produitInsuffisant = $verifQTe->produit->marque->libele ." ".$verifQTe->produit->libele ." ".$newQt*(-1) ." pc en moins"; 
                            // dd($produitInsuffisant);
                            return back()->with('echec',"Stock insufisant pour le produit $produitInsuffisant pour effectuer cette opératoin, approvisionner puis réessayer!");
                        }
                        $dataSave['id']=$key;
                        $dataSave['qt']=$k;
                        $dataSave['pt']=$v;
                        array_push($tabDataVenteProduit,$dataSave);
                        $onlyNewProduct[$key] = $newQt;
                    }
                }
            }
            if($tailleNewInput==$sizeOldInput && $tailleNewInput==$sameId ){
                // dd('echec meme produit', $stockUpdate); 
                return back()->with('echec',"Echec, la compassassion ou contre valeur ne peut pas se faire avec mêmes produits de la vente précédente!");
            }

            //dd("enregistremen",$tabDataVenteProduit , $sameId,"ancien absent", $stockUpdate, "ancien repris ",$needTrait, "only new ", $onlyNewProduct, $oldPaiement, $newPaiement);
            //on update stock des anciens non repris
            if (is_array($stockUpdate) && count($stockUpdate) > 0) {
                foreach($stockUpdate as $id=>$qt){
                    $getStock = ProduitDepot::where('produit_id', $id)->where('depot_id', $vente->depot_id)->first();
                    // dd($newQteUpdate, $getStock->quantite, $qt, $id);
                    $getStock->quantite +=(int) $qt;
                    $getStock->save();
                }
            }

            //on update stock des ancien repris
            if (is_array($needTrait) && count($needTrait) > 0) {
                foreach($needTrait as $aId=> $aQt){
                    $getStock = ProduitDepot::where('produit_id', $aId)->where('depot_id', $vente->depot_id)->first();
                    $getStock->quantite =(int) $aQt;
                    $getStock->save();
                }
            }
            
            //on update des onlynew
            if (is_array($onlyNewProduct) && count($onlyNewProduct) > 0) {
                foreach($onlyNewProduct as $nId=> $nQt){
                    $getStock = ProduitDepot::where('produit_id', $nId)->where('depot_id', $vente->depot_id)->first();
                    $getStock->quantite =(int) $nQt;
                    $getStock->save();
                }
            }
            

            //on enregistre la compassassion
            $statusPaiement = $newPaiement - $oldPaiement;
            $getSoldeLast = $vente->paiement->last()->solde;
            if($statusPaiement > 0){
                //on ajoute paiement
                $dataPaiement = [
                    "vente_id" => $vente->id,
                    "tranche" => $vente->paiement->count() +1,
                    "avance" => $statusPaiement,
                    "solde" => ($getSoldeLast > 0) ? $getSoldeLast - $statusPaiement : 0,
                    "net" => $newPaiement,
                    "completed" => true
                ];
                $addNewPaiement = Paiement::create($dataPaiement);
            }elseif($statusPaiement == 0){
                //on bouge rien
            }else{
                return back()->with('echec', "Le montant de versement de paiement est inférieur au précédent, réessayer!");
            }
            
            
            foreach($tabDataVenteProduit as $valeur){
                $dataCompassassion =[
                    'produit_id'=>$valeur['id'],
                    'vente_id'=>$vente->id,
                    'quantite'=>$valeur['qt'],
                    'prixU'=>(int)$valeur['pt']/(float)$valeur['qt'],
                    'prixT'=>$valeur['pt']
                ];
                $createCompassassion = Compassassion::create($dataCompassassion);
            }

             if($createCompassassion){
                // $createPaie= Paiement::create($data);
                $routeParam = 56745264509*$vente->id;
                $routeParam = ["vente"=>56745264509*$vente->id, "depot"=>$vente->depot_id];

                return to_route('venteShow',$routeParam);
            }

            return back()->with('echec',"Echec, erreur inattendue s'est produite, compassassion échouée !");
            // dd(vars: 'ok',$dataCompassassion, Compassassion::all());
        }
        return back()->with('echec',"Echec, impossible de trouver cette vente !");
    }

    public function destroy($id){
        $ref = Compassassion::find($id);
        if(!$ref){
           return back()->with('echec', 'Aucune donnée trouvée pour suppression.');
        }
        $date = Carbon::parse($ref->created_at)->toDateString(); // '2025-09-11'

        $delete = Compassassion::where('vente_id', $ref->vente_id)
            ->whereDate('created_at', $date);
        $firstDelete = $delete->first();
        if (!$firstDelete) {
            return back()->with('echec', 'Aucune donnée trouvée pour suppression.');
        }

        $netInit = $firstDelete->vente->paiement->first();
        $netFinal = $firstDelete->vente->paiement->last();
        $ajout = ($netFinal->net == $netInit->net) ? true : false;

        $depot = $delete->first()->vente->depot;
        $dateSearch = $firstDelete->created_at;
        if($ajout){
            $delete->delete();
            return to_route('compList', [
                'depot' => $depot->libele,
                'id'    => $depot->id,
            ])->with('success', 'Annulation de compassassion effectuée avec succès !');
        };
        $paiement = $firstDelete->vente->paiement
                ->sortBy('net')
                ->groupBy('net');
        
        if ($paiement->count() === 2) {
            $lastPaiement = $paiement->last();
            if ($lastPaiement) {
                foreach($lastPaiement as $cle=>$val){
                    $val->delete();
                }
            }
        }
       

        if ($paiement->count() > 2) {
            $listPaiementSup = $paiement->map(function ($group) use ($dateSearch) {
                    return $group->filter(function ($item) use ($dateSearch) {
                        return $item->created_at > $dateSearch;
                    });
                })->filter(function ($group) {
                    return $group->isNotEmpty();
                });
            $avance = 0;
            if ($listPaiementSup) {
                foreach($listPaiementSup as $m=> $paie){
                    $avance =  $paie[0]->avance;
                    break;
                }
                // on update net les compassassions apres cette date
                $listPaiementUpdate = $paiement
                    ->map(fn($group) => $group->filter(fn($item) => $item->created_at == $dateSearch))
                    ->filter(fn($group) => $group->isNotEmpty());

                $listPaiementUpdate->each(function ($group) use ($avance) {
                    $group->each(function ($item) use ($avance) {
                        $item->net -= $avance;
                        $item->save();
                    });
                });

                $listPaiementSup->each(function ($group) {
                    $group->each->delete();
                });
            }
        }
       
        $delete->delete();
        return to_route('compList', [
            'depot' => $depot->libele,
            'id'    => $depot->id,
        ])->with('success', 'Annulation de compassassion effectuée avec succès !');
    }
}
