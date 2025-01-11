<?php

namespace App\Http\Controllers;

use App\Models\Approvisionnement;
use App\Models\Depot;
use App\Models\Produit;
use Illuminate\Http\Request;

class ApprovisionnementController extends Controller
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
    public function create($depot)
    {
        $depot=Depot::where("libele",$depot)->first();
        $user = auth()->user();
        $produit = Produit::orderByDesc("marque_id")->get();
        return view("appro.create", compact("depot","user", "produit"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function showDepotAppro($depot)
    {
        $depot=Depot::where("libele",$depot)->first();
        $user = auth()->user();
        return view("appro.index",compact("user","depot"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Approvisionnement $approvisionnement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Approvisionnement $approvisionnement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Approvisionnement $approvisionnement)
    {
        //
    }
}
