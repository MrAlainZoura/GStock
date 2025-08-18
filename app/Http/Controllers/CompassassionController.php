<?php

namespace App\Http\Controllers;

use App\Models\Depot;
use App\Models\Vente;
use App\Models\Client;
use App\Models\Categorie;
use App\Models\ProduitDepot;
use Illuminate\Http\Request;
use App\Models\Compassassion;

class CompassassionController extends Controller
{
    public function index()
    {
        return Compassassion::all();
    }
    public function create($depot ,$vente_id){
        return back()->with('success', "Bientot disponible");
        $vente= Vente::find($vente_id);
        // dd($vente, $vente_id);
        if($vente){
            $cat = Categorie::all();
            $client =Client::all();
            $depot = $vente->depot;
            $produit = ProduitDepot::where("depot_id",$depot->id)->with("produit.marque","produit.marque.categorie")->get();
            return view("compassassion.create", compact("depot","cat","client","produit", 'vente'));
        }
        return back()->with('echec', "Demande mal formulée, veuillez réessayez !");
    }

    public function store(Request $request){
        dd($request->all());
    }
}
