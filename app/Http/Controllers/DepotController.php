<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Depot;
use App\Models\Vente;
use App\Models\Devise;
use App\Models\Produit;
use App\Models\Categorie;
use App\Models\Transfert;
use App\Models\ProduitDepot;
use Illuminate\Http\Request;
use App\Models\Approvisionnement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
            'monnaie'=>'required|string|max:255',
            'taux'=>'required|max:255',
        ]);

        if($validateDate->fails()){
            return $validateDate->errors();
        }
        $data = [
            'libele'=>$request->libele,
            'user_id'=>Auth::user()->id
        ];
        // dd($data, $dataDevise);
        $depot = Depot::create($data);
        if($depot){
            $dataDevise = [
                'libele'=>$request->monnaie, 
                'taux'=>$request->taux, 
                'depot_id'=>$depot->id
            ];
            $createDevise = Devise::firstOrCreate($dataDevise);
        }
        return back()->with('success','Depot ajouté avec success');
    }

    /**
     * Display the specified resource.
     */
    public function show($getDepot)
    {
        if(!is_numeric($getDepot)){
            $depot = Depot::where("libele",$getDepot)->first();
        }else{
            $getDepot = $getDepot/12726654;
            $depot = Depot::where("id",$getDepot)->first();
        }

        if(!$depot){
            return to_route('dashboard')->with('echec',"Choisissez un dépôt pour effectuer vos opérations!"); 
        }
        session(['depot' => $depot->libele]);
        session(['depot_id' => $depot->id]);
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
        $nombreVentes = count($totalVenteMois);
        $affichage = ($nombreVentes > 9) ? $nombreVentes : "0" . $nombreVentes;

        // (count($totalVenteMois)>9)?count($totalVenteMois):$totalVenteMois="0".count($totalVenteMois);
        (count($approMois1)>9)?$approMois=count($approMois1):$approMois="0".count($approMois1);
        (count($transMois1)>9)?$transMois=count($transMois1):$transMois="0". count($transMois1);
        
        $depot->totalVente = $affichage;
        $depot->totalApro = $totalApro;
        $depot->totalTrans = $totalTrans;
        $depot->approMois = $approMois;
        $depot->transMois = $transMois;
        $prodDepot = ProduitDepot::where("depot_id",$depot->id)->with('produit')->get();
        return view('depot.show',compact('prodDepot','depot','user','vendeurs','tabProdVendu'));
    }
    public function showProduit(string $depot, $id)
    {
        $id = $id/12;
        $depotData = Depot::where("libele",$depot)->where('id', $id)->first();
        if(!$depotData){
            return redirect()->back()->with('echec','Renseignement incorrect, recommencer');
        }
        $user = Auth::user();
        $cat = Categorie::orderBy('libele')->with('marque')->get();        
        $prodDepot = ProduitDepot::where("depot_id",$depotData->id)->with('produit.marque')->latest()->get();
        return view('depot.produit',compact('prodDepot','depotData','user','cat'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($depot)
    {
        // dd($depot);
        $getDepotInformation = Depot::where('libele', $depot)->where('id', session('depot_id'))->first();
        if($getDepotInformation != null){
            return view('depot.update',compact("getDepotInformation"));
        }
        return back()->with('echec',"Une erreur inattendue s'est produite");

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $depot)
    {
        $dossier ='logo';
        if (!Storage::disk('direct_public')->exists($dossier)) {
            Storage::disk('direct_public')->makeDirectory($dossier);
        }
        // dd($request->all());
        $validateDate = Validator::make($request->all(),
        [
            'id'=>'required',
            'libele'=>'required|string|max:255',
        ]);

        if($validateDate->fails()){
            // return $validateDate->errors();
        }
        $fichier = $request->file('logo');
        $type = ($fichier!=null)?$fichier->getClientOriginalExtension():null;
        
        $data = [
            'libele'=>$request->libele,
            'logo'=>($request->file('logo')!=null)? "$request->libele.$type":null,
            'email'=>$request->email,
            'contact1'=>$request->numPrincipal,
            'contact'=>$request->numSecond,
            'cpostal'=>$request->codeP,
            'pays'=>$request->pays,
            'province'=>$request->province,
            'ville'=>$request->ville,
            'avenue'=>$request->avenue,
            'idNational'=>$request->idNat,
            'numImpot'=>$request->impot,
            'autres'=>$request->autres,
            'remboursement_delay'=>$request->remboursement_delay
        ];
        $data = array_filter($data, function($val){return !is_null($val);});

        // $depotUpdate = Depot::where('id',session('depot_id'))->update($data);

        $depotUpdate = Depot::where('id',session('depot_id'))->first();
        $nomFichier = $depotUpdate->logo;
        // dd($nomFichier);
        
        if($depotUpdate->update($data)){
            if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
                $cheminFichier = $dossier . '/' . $nomFichier;
                $getDepot = Depot::find( $depotUpdate->id); 
                $nouveauNom = $getDepot ? $getDepot->logo : null;

                if ($nouveauNom !== null && Storage::disk('direct_public')->exists($cheminFichier)) {
                    Storage::disk('direct_public')->move($cheminFichier, $dossier . '/' . $nouveauNom);
                }
                // Enregistrer le nouveau fichier avec le nom d'origine
                $fichier = $request->file('logo')->storeAs(
                    $dossier,
                    $nouveauNom,
                    'direct_public'
                );
            }
       }

        $dataDevise[] = ["libele"=> $request->monnaie,'taux'=>$request->tauxDEchange];
        $dataDevise[] = ["libele"=> $request->monnaie1,'taux'=>$request->tauxDEchange1];
        
        $dataDevise = array_filter($dataDevise, function($val){return !is_null($val);});
        if($dataDevise != null){
           foreach ($dataDevise as $devise) {
                $libele = trim(strtolower($devise['libele'] ?? ''));
                $taux = (float)($devise['taux'] ?? null);

                if (empty($libele) || !is_numeric($taux)) {
                    continue; 
                }

                $taux = number_format((float) $taux, 2, '.', '');
                
                $createDevise = Devise::firstOrCreate([
                    'depot_id' => $depotUpdate->id,
                    'libele' => $libele,
                    'taux' => $taux,
                ]);
            }
        }
        return back()->with('success','Depot ajouté avec success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, Request $request)
    {
        $libele = $request->libele;
        $id = $id/13;
        $delete = Depot::where('id',$id)->where('libele', $libele)->first();
        if($delete){
            $delete->delete();
            return to_route('dashboard')->with('success','Depot supprimé avec succès');
        }
        return back()->with('echec','Echec depot introuvable');

    }

    public function depotSetting(string $depot){
         $depotData = Depot::with('depotUser')->where("libele",$depot)->where('id', session('depot_id'))->first();
                 
         $collaborateur = $depotData?->depotUser?->count() ?? 0;
         $tabCatMark = [];
         $countMark = 0;

         foreach($depotData->produitDepot as $k=>$v){

            if (array_key_exists($v->produit->marque->categorie->libele, $tabCatMark)) {
                if (!in_array($v->produit->marque->libele, $tabCatMark[$v->produit->marque->categorie->libele])) {
                    $tabCatMark[$v->produit->marque->categorie->libele][] = $v->produit->marque->libele;
                    $countMark+=1;
                } 
            } else {
                $tabCatMark[$v->produit->marque->categorie->libele] = [$v->produit->marque->libele];
                $countMark+=1;
            }
         }
        return view('depot.setting',compact('depotData', 'collaborateur','tabCatMark','countMark'));
    }
}
