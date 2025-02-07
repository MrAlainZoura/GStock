<?php

namespace App\Http\Controllers;

use App\Models\Depot;
use App\Models\Vente;
use App\Models\Client;
use App\Models\Categorie;
use Carbon\CarbonInterface;
use App\Models\ProduitDepot;
use App\Models\VenteProduit;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
        // dd($request->all());
        $validateDate = Validator::make($request->all(),
        [
            'nom_client'=>'string|required',
            'lieu_de_vente'=>'string|required',
            'contact_client'=>'required|max:255',
            'produits'=>'array|required'
        ]);
        if($validateDate->fails()){
            return back()->with('echec',$validateDate->errors());
        }
        $a =Auth::user()->name[0] ;
        $b = (Auth::user()->postnom !=null)?Auth::user()->postnom[0]:null ;
        $c =(Auth::user()->prenom !=null)? Auth::user()->prenom[0]:null;

        $initialUser = "$a$b$c";
        $numero = Vente::where("user_id", Auth::user()->id)->where("created_at",'like','%'.Carbon::now()->format('Y-m-d').'%')->count()+1;
        $numero =($numero < 10)?"0$numero":$numero;
        $dataClient = [
            'name'=>$request->nom_client,
            'prenom'=>$request->prenom,
            'tel'=>$request->contact_client,
            'adresse'=>$request->adresse,
            "genre"=>$request->genre
        ];
        $filterDataClient = array_filter($dataClient, function($val){return !is_null($val);});
        $tel = "0".substr($filterDataClient['tel'], 4);
        $findClient = Client::where('tel',$filterDataClient['tel'])->orWhere('tel',)->get();
        $client_id ="";
        if(count($findClient) > 0){
            $client_id =$findClient[0]->id;
        }else{
            $createClient = Client::create($filterDataClient);
            $client_id = $createClient->id;
        }
        $tabDataVenteProduit = [];

        foreach($request->produits as $key=>$val){
            $verifQTe = ProduitDepot::where('produit_id',$key)->where('depot_id',$request->depot_id/98123)->first();
            foreach($val as $k=>$v){

                if($verifQTe->quantite >= $k){
                    $dataSave['id']=$key;
                    $dataSave['qt']=$k;
                    $dataSave['pt']=$v;
                    array_push($tabDataVenteProduit,$dataSave);
                }
            }
        }
        // dd($tabDataVenteProduit);
        if($tabDataVenteProduit!=null){
            $dataVente = [
                'user_id'=>Auth::user()->id,
                'depot_id'=>$request->depot_id/98123,
                'code'=>"N°$numero/$initialUser",
                'client_id'=>$client_id,
                'type'=>$request->lieu_de_vente
            ] ;

            $createVente = Vente::create($dataVente);
            if($createVente){
                foreach($tabDataVenteProduit as $valeur){
                    // dd( $valeur['id'], $valeur['qt'], $valeur['pt']);
                    $dataVenteProduit =[
                        'produit_id'=>$valeur['id'],
                        'vente_id'=>$createVente->id,
                        'quantite'=>$valeur['qt'],
                        'prixU'=>(int)$valeur['pt']/(int)$valeur['qt'],
                        'prixT'=>$valeur['pt']
                    ];
                    $createVenteProduit = VenteProduit::create($dataVenteProduit);
                    if($createVenteProduit){
                        return back()->with("success","Vente effectué avec succes, numéro $createVente->code par ".Auth::user()->name);
                    }
                }
            }
        }        
        return back()->with("echec","Vente echouée, une erreur inattendue s'est produite.");

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
