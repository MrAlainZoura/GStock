<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Depot;
use App\Models\Vente;
use App\Models\Produit;
use App\Models\Categorie;
use App\Models\Transfert;
use App\Models\ProduitDepot;
use Illuminate\Http\Request;
use App\Models\Approvisionnement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DepotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $depot = Depot::latest()->get();
        return response()->json(['success'=>true, 'data'=>$depot]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $validateDate = Validator::make($request->all(),
        [
            'libele'=>'required|string|max:255',
        ]);

        if($validateDate->fails()){
            return $validateDate->errors();
        }
        $data = [
            'libele'=>$request->libele,
            'user_id'=>Auth::user()->id
        ];

        $depot = Depot::create($data);
        return back()->with('success','Depot ajouté avec success');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $id = $id/12726654;
        $depot = Depot::where("id",$id)->first();
        session(['depot' => $depot->libele]);
        $user = Auth::user();
        $cat= Categorie::all();
        $mois = Carbon::now()->format('m');

        $vendeurs = Vente::selectRaw('user_id, COUNT(*) as count')
                    ->groupBy('user_id')
                    ->orderByDesc('count')
                    ->whereMonth('created_at', (int)$mois)
                    ->where('depot_id', $depot->id)
                    ->take(2)
                    ->get();
        $approMois1 = Approvisionnement::whereMonth('created_at', (int)$mois)
                    ->where('depot_id', $depot->id)
                    ->get();
        $transMois1 = Transfert::whereMonth('created_at', (int)$mois)
                    ->where('depot_id', $depot->id)
                    ->get();
        $totalVenteMois = Vente::where('depot_id', $depot->id)->whereMonth('created_at',$mois)->get();
        
        $tabProdVendu = [];
        foreach($totalVenteMois as $key=>$value){
            foreach($value->venteProduit as $k=>$v){
                if(array_key_exists($v->produit->marque->categorie->libele." ".$v->produit->marque->libele." ".$v->produit->libele , $tabProdVendu)){
                    $newQt = $tabProdVendu[$v->produit->marque->categorie->libele." ".$v->produit->marque->libele." ".$v->produit->libele] +$v->quantite;
                    $tabProdVendu[$v->produit->marque->categorie->libele." ".$v->produit->marque->libele." ".$v->produit->libele] = $newQt;
                }else{
                    $tabProdVendu[$v->produit->marque->categorie->libele." ".$v->produit->marque->libele." ".$v->produit->libele]=$v->quantite;
                }

            }
        }
        $totalApro = 0;
        foreach($approMois1 as $cl=>$vl){
            $totalApro+=$vl->quantite;
        }

        $totalTrans = 0;
        foreach($transMois1 as $cle=>$valeur){
            foreach($valeur->produitTransfert as $c=>$vl){
                $totalTrans+=$vl->quantite;
            }
        }
        arsort($tabProdVendu);
        (count($totalVenteMois)>9)?:$totalVenteMois="0".count($totalVenteMois);
        (count($approMois1)>9)?$approMois=count($approMois1):$approMois="0".count($approMois1);
        (count($transMois1)>9)?$transMois=count($transMois1):$transMois="0". count($transMois1);
        
        $depot->totalVente = $totalVenteMois;
        $depot->totalApro = $totalApro;
        $depot->totalTrans = $totalTrans;
        $depot->approMois = $approMois;
        $depot->transMois = $transMois;
        $prodDepot = ProduitDepot::where("depot_id",$id)->with('produit')->get();
        return view('depot.show',compact('prodDepot','depot','user','vendeurs','tabProdVendu'));
    }
    public function showProduit(string $depot)
    {
        $depotData = Depot::where("libele",$depot)->first();
        $user = Auth::user();
        $cat = Categorie::orderBy('libele')->with('marque')->get();        
        $prodDepot = ProduitDepot::where("depot_id",$depotData->id)->with('produit.marque')->latest()->get();
        return view('depot.produit',compact('prodDepot','depotData','user','cat'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Depot $depot)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validateDate = Validator::make($request->all(),
        [
            'id'=>'required',
            'libele'=>'required|string|max:255',
        ]);

        if($validateDate->fails()){
            return $validateDate->errors();
        }
        $data = [
            'libele'=>$request->libele,
        ];

        $depot = Depot::where('id',$id)->update($data);
        return response()->json(['success'=>true, 'data'=>Depot::find($id)]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $delete = Depot::where('id',$id)->delete();
        if(!$delete){
            return response()->json(['success'=>true, 'data'=>'echec de suppression']);
        }
        return response()->json(['success'=>true, 'data'=>'Suppression reussie!']);
    }
}
