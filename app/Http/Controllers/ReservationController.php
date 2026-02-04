<?php

namespace App\Http\Controllers;

use App\Models\Depot;
use App\Models\Client;
use App\Models\Categorie;
use App\Models\Reservation;
use App\Models\ProduitDepot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReservationController extends Controller
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
    public function create($depot_id)
    {
        // dd('redirect vue create');
        $depot= Depot::where('id', $depot_id)->first();
        $cat = Categorie::all();
        $client =Client::all();
        $produit = ProduitDepot::where("depot_id",$depot->id)->with("produit.marque","produit.marque.categorie")->get();
        return view("reservation.create", compact("depot","cat","client","produit"));
    
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateDate = Validator::make($request->all(),
        [
            'nom_client',
            'lieu_de_vente'=>'string|required',
            'contact_client'=>'max:255',
            'produits'=>'array|required'
        ]);
        if($validateDate->fails()){
            // return back()->with('echec',$validateDate->errors());
        }
        dd($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(Reservation $resevation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reservation $resevation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reservation $resevation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $resevation)
    {
        //
    }
}
