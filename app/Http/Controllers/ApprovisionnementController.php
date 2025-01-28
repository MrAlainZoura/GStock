<?php

namespace App\Http\Controllers;

use App\Models\Depot;
use App\Models\Produit;
use App\Models\ProduitDepot;
use Illuminate\Http\Request;
use App\Models\Approvisionnement;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
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
    public function edit(string $appro)
    {
        //
    }

    public function show(string $appro){

    }
    public function confirm(string $appro){

        $date = Carbon::now("Africa/Kinshasa");
        $string = $appro;
        $id = "";
        $name="";
        $libele="";
        // dd($appro);
        $parts = explode("adft", $string);
        $size = count($parts);
        $prod_id = $parts[0]/56745264509;
        $id = $parts[1]/6789012345;
        $userName = Auth::user()->name;
        $userPrenom = (Auth::user()->prenom!=null) ? Auth::user()->prenom : Auth::user()->postnom;
        $data=[
            'confirm'=>true,
            'produit_id'=>(int)$prod_id, 
            'receptionUser'=> "$userName $userPrenom",
            'updated_at'=>$date->format('Y-m-d H:i:s')];
        $updateConfirm = Approvisionnement::where('id',$id)->where('produit_id',$prod_id)->where('confirm',false)->first();
        if($updateConfirm->user_id==Auth::user()->id)
        {
            return back()->with('echec',"Desolé, vous ne pouvez pas confirmé votre prore approvisionnement");
        }
        $updateConfirm->update($data);
        if($updateConfirm){
        return back()->with('success',"Approvisionnement confirmé avec succcès par $userName $userPrenom");
        }
        return back()->with('echec',"Desolé une erreur inattendue s'est produite, réessayez plus tard");
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
