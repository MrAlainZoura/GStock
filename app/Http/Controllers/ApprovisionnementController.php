<?php

namespace App\Http\Controllers;

use App\Models\Depot;
use App\Models\Produit;
use App\Models\ProduitDepot;
use App\Models\Transfert;
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
       // "produit.marque","produit.marque.categorie"
        $produit = ProduitDepot::where("depot_id",$depot->id)->with("produit.marque","produit.marque.categorie")->get();
        // $produit = Produit::orderByDesc("marque_id")->with('marque')->get();
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
        $approEchec = 0;
        foreach($request->produits as $key => $value){
            $findProduit = Produit::where('id',$key)->first();
            if($findProduit != null){
                $dataApro =  [
                    'user_id' =>$request->user_id,
                    'depot_id' =>$request->depot_id,
                    'produit_id' =>$key,
                    'quantite' =>$value
                ];
                $createAppro = null;
                ($value > 0)?$createAppro = Approvisionnement::create($dataApro):$approEchec+=+1;
                
                if($createAppro != null){
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
        ($approEchec > 0) ? $message="Approvisionnement effectué avec succcès sauf ceux ($approEchec) avec quantité 0" : $message="Approvisionnement effectué avec succcès";
        return back()->with('success',$message);
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
        return back()->with('success',"Bientôt disponible"); 
    }

    public function show(string $appro){
        return back()->with('success',"Bientôt disponible"); 
    }
    public function confirmAll(Request $request, string $appro){
        
    }
    public function confirm(string $appro, $action){
        // dd($appro, $action);
        $date = Carbon::now("Africa/Kinshasa");
        $userName = Auth::user()->name;
        $userPrenom = (Auth::user()->prenom!=null) ? Auth::user()->prenom : Auth::user()->postnom;
            
        if($action=='one'){
            $string = $appro;
            $id = "";
            $name="";
            $libele="";
            $parts = explode("adft", $string);
            $size = count($parts);
            $prod_id = $parts[0]/56745264509;
            $id = $parts[1]/6789012345;
            $data=[
                'confirm'=>true,
                'receptionUser'=> "$userName $userPrenom",
                'updated_at'=>$date->format('Y-m-d H:i:s')];
            $updateConfirm = Approvisionnement::where('id',$id)->where('produit_id',$prod_id)->where('confirm',false)->first();
            if($updateConfirm==null){
                return back()->with('echec',"Desolé, vous ne pouvez pas confirmé approvisionnement deux fois");
            }
            if (
                 $updateConfirm->user_id == Auth::user()->id &&
                 !in_array(Auth::user()->user_role->role->libele, ['Administrateur', 'Super admin'])
             ) {
                 return back()->with('echec', "Désolé, vous ne pouvez pas confirmer votre propre approvisionnement");
             }
             if($updateConfirm->origine != null){
                 $updateConfirmAll = Approvisionnement::where('origine',$updateConfirm->origine)->where('confirm',false);
                 $dataUpdateAll = [
                     'receptionUser'=> "$userName $userPrenom",
                     'confirm'=>true,
                     'updated_at'=>$date->format('Y-m-d H:i:s')];
                 if($updateConfirmAll->update($dataUpdateAll)){
                     $updateConfirmTransfert = Transfert::where("code",$updateConfirm->origine)
                                               ->update($dataUpdateAll);
                 }
                 return back()->with('success',"Approvisionnement confirmé avec succcès par $userName $userPrenom");
             }
             if($updateConfirm->update($data)){
                 return back()->with('success',"Approvisionnement confirmé avec succcès par $userName $userPrenom"); 
             }
        }elseif($action =="all"){
            if (
                 !in_array(Auth::user()->user_role->role->libele, ['Administrateur', 'Super admin'])
             ) {
                 return back()->with('echec', "Désolé, vous ne pouvez pas confirmer tous les approvisionnements à la fois");
             }
             $dataAllConfirmDepot = [
                'confirm'=>true,
                'receptionUser'=> "$userName $userPrenom",
                'updated_at'=>$date->format('Y-m-d H:i:s')
            ];
             $getAproDepotNotConfirmAll = Approvisionnement::where("depot_id", session('depot_id'))->where('confirm', false);
             $tabOrigin=[];
             foreach( $getAproDepotNotConfirmAll->get() as $key => $value ){
                if($value->origine != null && !in_array($value->origine , $tabOrigin)){
                    array_push( $tabOrigin, $value->origine );
                    $updateConfirmTransfertOrigne = Transfert::where("code",$value->origine)
                                                    ->update($dataAllConfirmDepot);    
                }
            }
            if($getAproDepotNotConfirmAll->update($dataAllConfirmDepot)){
                 return back()->with('success',"Approvisionnement confirmé avec succcès par $userName $userPrenom"); 
                }else{
                 return back()->with('success',"Pas d'approvisionnement non confirmé !"); 
             }
            // dd($tabOrigin);
        }else{
            return back()->with('echec',"Desolé une erreur inattendue s'est produite, réessayez plus tard");
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Approvisionnement $approvisionnement)
    {
        return back()->with('success',"Bientôt disponible"); 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Approvisionnement $approvisionnement)
    {
        return back()->with('success',"Bientôt disponible"); 
    }
}
