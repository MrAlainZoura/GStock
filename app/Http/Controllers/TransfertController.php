<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Depot;
use App\Models\Produit;
use App\Models\Transfert;
use App\Models\ProduitDepot;
use Illuminate\Http\Request;
use App\Models\Approvisionnement;
use App\Models\ProduitTransfert;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TransfertController extends Controller
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
    public function create($var_depot)
    {
        // dd(Transfert::all(), Approvisionnement::latest()->first(), ProduitDepot::latest()->first(), $var_depot);
        $depot=Depot::where("libele",$var_depot)->with('produitDepot')->first();
        $produit = ProduitDepot::where("depot_id",$depot->id)->with('produit.marque')->get();
        $depotList=Depot::where("libele",'!=',$var_depot)->get();
        return view("transfert.create",compact("produit","depot","depotList"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateDate = Validator::make($request->all(),
        [
            'produits'=>'required|array',
            'destination'=>'required',
            'depot_id'=>'required|exists:depots,id',
        ]);

        if($validateDate->fails()){
            return back()->with('echec',$validateDate->errors());
        }
        // dd($request->all(),Auth::user());
        $a =Auth::user()->name[0] ;
        $b = (Auth::user()->postnom !=null)?Auth::user()->postnom[0]:null ;
        $c =(Auth::user()->prenom !=null)? Auth::user()->prenom[0]:null;

        $initialUser = "$a$b$c";
        $numero = Transfert::where("user_id", Auth::user()->id)->where("created_at",'like','%'.Carbon::now()->format('Y-m-d').'%')->count()+1;
        // dd(Transfert::all(),$numero);
        $code =$initialUser. Carbon::now()->format('Ymd')."N$numero";
        $dataTrans = [ 
            "user_id"=>Auth::user()->id,
            "destination"=>$request->destination,
            "code"=>$code,
            "depot_id"=>$request->depot_id,
            "description"=>$request->description
        ];
        $depotDestinatin = Depot::where("libele", $request->destination)->first();
        $verifTransData =[];
        foreach($request->produits as $ke => $val){
            $findProduit = ProduitDepot::where('produit_id',$ke)->where('depot_id',$request->depot_id)->first();
            if($findProduit != null && $findProduit->quantite > $val){
                $verifTransData [$ke] = $val;
            }
        }
        if($verifTransData!=null) {
        $transfertCreate= Transfert::create($dataTrans);
            foreach($verifTransData as $key => $value){
                $dataPT= [
                    "transfert_id"=>$transfertCreate->id,
                    "produit_id"=>$key,
                    "quantite"=>$value
                ];
                $produitTransfertCreate = ProduitTransfert::create($dataPT);
                $dataApro =  [
                    'user_id' =>Auth::user()->id,
                    'depot_id' =>$depotDestinatin->id,
                    'produit_id' =>$key,
                    'quantite' =>$value,
                    'origine'=>$transfertCreate->code
                ];
                // dd($dataApro);
                $createAppro = Approvisionnement::create($dataApro);
                if($createAppro){
                    $findDepotProduit = ProduitDepot::where('depot_id',$depotDestinatin->id)->where('produit_id',$key)->first();
                    $findDepotProduitOrigine = ProduitDepot::where('depot_id',$request->depot_id)->where('produit_id',$key)->first();
                    $today = Carbon::now()->format('Y-m-d H:i:s');
                    $setQtOrigine = $findDepotProduitOrigine->quantite - $value;
                    $findDepotProduitOrigine->update(['quantite'=> $setQtOrigine, 'updated_at'=>$today]);
                    if($findDepotProduit != null){
                        $setQt =$value + $findDepotProduit->quantite;
                        $findDepotProduit->update(['quantite'=> $setQt, 'updated_at'=>$today]);
                    }else{
                        $dataDepotProduit = ['depot_id'=>$depotDestinatin->id,
                                            'produit_id'=>$key,
                                            'quantite'=>$value] ;
                        $createProduit = ProduitDepot::createOrFirst($dataDepotProduit);
                    }
    
                }
            }
        }else{

            return back()->with('echec',"Une erreur inattendue s'est produite, veuillez réessayer");
        }
        return back()->with('success',"Transfert effectué avec succcès");
    
    }

    /**
     * Display the specified resource.
     */
    public function showDepotTrans($depot){
        $findDepotId = Depot::where('libele',$depot)->first();
        $findTransDepot = Transfert::where('depot_id',$findDepotId->id)->with('produitTransfert')->get();
        return view('transfert.index',compact('findTransDepot'));

    }
    public function show($transfert)
    {
        // dd($transfert);
        
        $parts = explode("adft", $transfert);
        $size = count($parts);
        $code = $parts[0];
        $id = $parts[1]/6789012345;
        $findTransDetails = Transfert::where("id",$id)->where('code', $code)->first();
        return view('transfert.show',compact('findTransDetails'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($transfert)
    {
        return back()->with('success',"Bientôt disponible"); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transfert $transfert)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $transfert)
    {
        return back()->with('success',"Bientot disponible !");
    }
}
