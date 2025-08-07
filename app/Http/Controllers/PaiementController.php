<?php

namespace App\Http\Controllers;

use App\Models\Depot;
use App\Models\Vente;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PaiementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(Request $request, $vente)
    {
        $id = $vente/8943;
        $getVente = Paiement::where('vente_id', $id)->latest()->first();
        $solde = $getVente->solde;
        $versement = $request->paiment;
        $newSolde = $getVente->solde - $versement;
        $data = [
            "vente_id"=>$id,
            "tranche"=>$getVente->tranche+1,
            "avance"=>$versement,
            "solde"=>$newSolde,
            "net"=>$getVente->net,
            "completed"=>($newSolde==0)?true:false
        ];

        if($versement <= $solde){
            $createPaie= Paiement::create($data);
            $routeParam = 56745264509*$createPaie->vente_id;
            return to_route('venteShow',$routeParam);
        }
        return back()->with('echec',"Une erreur s'est produite");    
    }

    /**
     * Display the specified resource.
     */
    public function show( $paiement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Paiement $paiement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Paiement $paiement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Paiement $paiement)
    {
        //
    }

    public function creance($depot){

        $depot = Depot::where('libele', $depot)->where('id', session('depot_id'))->first();
        $depotId = $depot->id;
        $depotCreance = Vente::with(['client', 'venteProduit']) 
                        ->whereHas('paiement', function ($query) {
                            $query->where('completed', false);
                        })
                        ->where('depot_id', $depotId)
                        ->get();
        $tabSyntese=[];
        
        foreach($depotCreance as $k=>$v){
            if(!array_key_exists($v->vente_id, $tabSyntese)){
                foreach($v->venteProduit as $c=>$val){
                    $prod[] = $val->produit->marque->libele.' '.$val->produit->libele;
                }
                foreach($v->paiement as $cl=>$vl){
                    $tranche[]=$vl->avance. " - ".$vl->solde;
                }
                $dernierVersement = count($v->paiement)-1;
                $completed = $v->paiement[$dernierVersement]->completed;
                $tabSyntese[$v->id] = [
                    'vendeur'=>$v->user->name." ".$v->user->postnom." ".$v->user->prenom,
                    'client'=>['nom'=>$v->client->name.' '.$v->client->prenom.' '.$v->client->postnom,'tel'=> $v->client->tel,'date'=> $v->created_at] ,
                    'prod'=>$prod, 
                    'tranche'=>$tranche, 
                    'net'=>$v->paiement[0]->net,
                    'completed'=>$completed,
                    'devise'=>$v->devise->libele
                ];
                $prod=[];
                $tranche=[];
            }
    
        }
        // dd($tabSyntese, 'ok');
        return view('vente.creance', compact('tabSyntese',"depot"));
    }
}
