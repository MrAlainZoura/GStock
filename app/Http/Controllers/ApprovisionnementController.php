<?php

namespace App\Http\Controllers;

use App\Models\Depot;
use App\Models\Produit;
use App\Models\ProduitDepot;
use Illuminate\Http\Request;
use App\Models\Approvisionnement;
use Illuminate\Support\Facades\Validator;

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
        $produit = Produit::orderByDesc("marque_id")->with('marque')->get();
        return view("appro.create", compact("depot","user", "produit"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $validateDate = Validator::make($request->all(),
        [
            'produits'=>'required',
            'depot_id'=>'required|exists:depots,id',
            'user_id'=>'required|exists:users,id',
        ]);

        if($validateDate->fails()){
            return back()->with('echec',$validateDate->errors());

        }
        foreach($request->produits as $key => $value){
            $findProduit = Produit::where('id',$key)->first();
            if($findProduit != null){
                $dataApro =  [
                    'user_id' =>$request->user_id,
                    'depot_id' =>$request->depot_id,
                    'produit_id' =>$key,
                    'quantite' =>$value
                ];
                $createAppro = Approvisionnement::create($dataApro);
                if($createAppro){
                    $findDepotProduit = ProduitDepot::where('depot_id',$request->depot_id)->where('produit_id',$key)->first();
                    if($findDepotProduit != null){
                        $setQt =$value + $findDepotProduit->quantite;
                        $findDepotProduit->update(['quantite'=> $setQt]);
                    }else{
                        $dataDepotProduit = ['depot_id'=>$request->depot_id,
                                            'produit_id'=>$key,
                                            'quantite'=>$value] ;
                        $createProduit = ProduitDepot::createOrFirst($dataDepotProduit);
                    }

                }
            }
        }
        return back()->with('success',"Approvisionnement effectué avec succcès");
    }

    /**
     * Display the specified resource.
     */
    public function showDepotAppro($depot)
    {
        $depot=Depot::where("libele",$depot)->first();
        $user = auth()->user();
        $appro = Approvisionnement::all()->count();
        return view("appro.index",compact("user","depot","appro"));
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
