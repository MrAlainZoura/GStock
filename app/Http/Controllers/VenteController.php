<?php

namespace App\Http\Controllers;

use App\Models\Depot;
use App\Models\Vente;
use App\Models\Client;
use App\Models\Devise;
use App\Models\Paiement;
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
    public function create($depot, $depot_id)
    {
        // if(session('depot') === null){
        //     return to_route('dashboard');
        // }
        $depot= Depot::where("libele",$depot)->where('id', $depot_id)->first();
        $cat = Categorie::all();
        $client =Client::all();
        $produit = ProduitDepot::where("depot_id",$depot->id)->with("produit.marque","produit.marque.categorie")->get();
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
            'contact_client'=>'max:255',
            'produits'=>'array|required'
        ]);
        if($validateDate->fails()){
            return back()->with('echec',$validateDate->errors());
        }

        preg_match('/^(\d+)-(.*)$/', $request->monnaie, $m)
            ? [$devise, $libele] = [$m[1], $m[2]]
            : [$devise, $libele] = [null, null];

        $getDeviseId = Devise::where('id', $devise)->where('libele',$libele)->first();
        $devise_id = ($getDeviseId) ? $getDeviseId->id : null;
        // dd($devise_id);

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
        $client_id ="";
        $tel=$request->contact_client;
        $findClient =null;
        if($tel !=null){
            (strlen($tel)<=10)?$lonTel="+243".substr($tel, 1):$lonTel=$tel;
            $findClient = Client::where('tel',$tel)->orWhere('tel',$lonTel)->first();
            if($findClient != null){
                $client_id =$findClient->id;
            }else{
                $createClient = Client::create($filterDataClient);
                $client_id = $createClient->id;
            }
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
                    $newQt =$verifQTe->quantite - $k;
                    $verifQTe->update(['quantite'=>$newQt, 'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')]);
                }
            }
        }
        if($tabDataVenteProduit!=null){
            $dataVente = [
                'user_id'=>Auth::user()->id,
                'depot_id'=>$request->depot_id/98123,
                'code'=>"N°$numero/$initialUser",
                'client_id'=>$client_id,
                'type'=>$request->lieu_de_vente,
                'devise_id'=>$devise_id,
                'updateTaux'=>$request->updateDevise
            ] ;

            $createVente = Vente::create($dataVente);
            if($createVente){
                $netPayer = 0;
                foreach($tabDataVenteProduit as $valeur){
                    $dataVenteProduit =[
                        'produit_id'=>$valeur['id'],
                        'vente_id'=>$createVente->id,
                        'quantite'=>$valeur['qt'],
                        'prixU'=>(int)$valeur['pt']/(float)$valeur['qt'],
                        'prixT'=>$valeur['pt']
                    ];
                    $netPayer+=(float)$valeur['pt'];
                    // dd($dataVenteProduit, $netPayer);
                    $createVenteProduit = VenteProduit::create($dataVenteProduit);
                }
                $checkTranche = filter_var($request->tranche, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

                $dataPaiement  = [
                    'vente_id'=>$createVente->id,
                    "tranche"=>1,
                    "avance"=>($checkTranche==true)?(float)$request->trancheP:$netPayer,
                    "solde"=>($checkTranche==true)?$netPayer - (float)$request->trancheP:0,
                    "net"=>$netPayer,
                    "completed"=>($checkTranche==true)?false:true
                ];
                $createPaiement = Paiement::create($dataPaiement);

                if($createVenteProduit){
                     $routeParam = 56745264509*$createVente->id;
                    return to_route('venteShow',$routeParam);
                }
            }
        }        
        return back()->with("echec","Vente echouée, une erreur inattendue s'est produite, produit en rupture de stock.");

    }

    /**
     * Display the specified resource.
     */
    public function showDepotVente($depotVar, $id){
        // dd($depotVar);
        // if(session('depot') === null){
        //     return to_route('dashboard');
        // }
        $depot= Depot::where('libele',$depotVar)->where('id', $id)->first();
        if($depot){
            // dd($depot->vente, $depot->devise);
            return view('vente.index', compact('depot')) ;
        }else{
            return back()->with("echec","Erreur demande introuvable");
        }

    }
    public function show( $vente)
    {
        $id= $vente/56745264509;
        $detailVente = Vente::where('id',$id)->first();
        if($detailVente == null){
            return back()->with('echec','Renseignements fournient sont invalides');
        }
        session(['depot' => $detailVente->libele]);
        session(['depot_id' => $detailVente->id]);
        $depotId = $detailVente->depot->id;
        $userRole = Auth::user()->user_role->role->libele;
        $user_id = Auth::user()->id;
        $userDepotAffectation = Auth::user()->depotUser;

        $detailVente->devise = $detailVente->devise ? $detailVente->devise->libele : "inc";
        $detailVente->taux = ($detailVente->updateTaux != null) ? $detailVente->updateTaux : 1;

        $existAffectation = $userDepotAffectation->filter(function ($u) use ($depotId) {
                        return isset($u->depot_id) && $u->depot_id === $depotId;
                    })->first();

         $roleAutorises = ['Administrateur', 'Super admin'];
        if (!in_array($userRole, $roleAutorises)) {
            if($existAffectation === null){
                // dd($affectation,"pas d'acces dans ce depot");
                return to_route('dashboard')->with('echec',"Vous n'avez pas accès à ce depot!"); 
            }
            // dd($detailVente->venteProduit[0]->produit->image, $detailVente->taux, $detailVente->devise);
            return view('vente.show', compact('detailVente')) ;
        }
        if($userRole === $roleAutorises[0]){
            $adminDepotCreateur = Auth::user()->depot->filter(function ($u) use ($user_id) {
                return isset($u->user_id) && $u->user_id === $user_id;
            })->first();
            // dd(  'administrateur', $adminDepotCreateur); 
            if( $adminDepotCreateur === null ){
                return to_route('dashboard')->with('echec',"Vous n'avez pas accès à ce depot!"); 
            }
            return view('vente.show', compact('detailVente')) ;
        }
        if($userRole === $roleAutorises[1]){
            return view('vente.show', compact('detailVente')) ;
        }   
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( $vente)
    {
        return back()->with("success","Bientôt disponible");
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
    public function destroy($vente)
    {
        if(!in_array(Auth::user()->user_role->role->libele,  ['Administrateur', 'Super admin'])){
            return back()->with("echec","Vous ne disposez pas de droit necessaire pour effectuer cette action");
        }

        $idVente = $vente/56745264509;
        $deleteVente = Vente::where('id', $idVente)->first();
       
        foreach($deleteVente->venteProduit as $c=>$v){
            $verifQTe = ProduitDepot::where('depot_id',$deleteVente->depot_id)
                ->where('produit_id',$v->produit_id)
                ->first();
            $newQt =$verifQTe->quantite + $v->quantite;
            $verifQTe->update(['quantite'=>(int)$newQt]);
        }

        if( $deleteVente->delete())  {
            return back()->with("success","Vente effacée avec succès");
        }
        return back()->with("echec","Une erreur s'est produite, veuillez réessayer plus tard !");
    }
     public function restore($venteId){

        if(!in_array(Auth::user()->user_role->role->libele,  ['Administrateur', 'Super admin'])){
            return back()->with("echec","Vous ne disposez pas de droit necessaire pour effectuer cette action");
        }
        $id = $venteId/12;
        $vente = Vente::with(['venteProduit', 'paiement'])->onlyTrashed()->find($id);
        if($vente){
            $vente->restore();
            //UPDATE QTE
            foreach($vente->venteProduit as $c=>$v){
                $verifQTe = ProduitDepot::where('depot_id',$vente->depot_id)
                    ->where('produit_id',$v->produit_id)
                    ->first();
                $newQt = $verifQTe->quantite - $v->quantite;
                $verifQTe->update(['quantite'=>(int)$newQt]);
            }
            return back()->with('success', "Vente restorée avec succès !");
        }
        return back()->with('success', "Erreur, renseignement fourni incorrect !");
     }

     public function venteTrashed($depot){
        // dd($depot/12);
        if(!in_array(Auth::user()->user_role->role->libele,  ['Administrateur', 'Super admin'])){
            return back()->with("echec","Vous ne disposez pas de droit necessaire pour effectuer cette action");
        }
        $depot_id = $depot/12;
        $depot= Depot::where('id', $depot_id)->first();
        if($depot){
            // dd($depot->vente, $depot->devise);
           $vente= Vente::where('depot_id', $depot->id)
           ->onlyTrashed()
           ->with([
                'venteProduit' => function ($query) {
                    $query->withTrashed();
                },
                'paiement' => function ($query) {
                    $query->withTrashed();
                }
            ])->get();
            return view('vente.trashed', compact('depot', 'vente')) ;
        }else{
            return back()->with("echec","Erreur demande introuvable");
        }
     }
     public function forcedelete($vente){
        if(!in_array(Auth::user()->user_role->role->libele,  ['Administrateur', 'Super admin'])){
            return back()->with("echec","Vous ne disposez pas de droit necessaire pour effectuer cette action");
        }
        $id = $vente/12;
        $vente = Vente::onlyTrashed()->find($id);
        // dd($vente);
        if($vente){
            $vente->forceDelete();
            return back()->with('success', "Vente supprimée définitivement avec succès !");
        }
        return back()->with('echec', "Suppression échouée, erreur inattendue s'est produite!");
     }
}
