<?php

namespace App\Http\Controllers;

use App\Models\Depot;
use App\Models\Vente;
use App\Models\Client;
use App\Models\Categorie;
use App\Models\ProduitDepot;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Carbon\CarbonInterface;

class VenteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Vente::all();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($depot)
    {
        $depot= Depot::where("libele",$depot)->first();
        $cat = Categorie::all();
        $client =Client::all();
        $produit = ProduitDepot::where("depot_id",$depot->id)->with("produit.marque")->get();
        return view("vente.create", compact("depot","cat","client","produit"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $depot)
    {
        dd($request->all());
        return back()->with("success","bientot disponible");
    }

    /**
     * Display the specified resource.
     */
    public function showDepotVente($depot){
        return back()->with("success","bientot disponible");

    }
    public function show(Vente $vente)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vente $vente)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vente $vente)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vente $vente)
    {
        //
    }
}
