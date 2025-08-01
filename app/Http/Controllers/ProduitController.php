<?php

namespace App\Http\Controllers;

use Excel;
use App\Models\Depot;
use App\Models\Marque;
use App\Models\Produit;
use App\Models\Categorie;
use App\Models\ProduitDepot;
use Illuminate\Http\Request;
use App\Imports\ProduitImport;
use App\Models\Approvisionnement;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProduitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $produit = Produit::latest()->get();
        return response()->json(['success'=>true, 'data'=>$produit]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tab = Categorie::orderby('libele')->with('marque')->get();
        $depot_id=Depot::where('libele',session('depot'))->first()->id;
        return view('produit.create', compact('tab','depot_id'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $dossier = 'produit';
        // Vérifier si le dossier existe, sinon le créer
        // if (!Storage::disk('public')->exists($dossier)) {
        //     Storage::disk('public')->makeDirectory($dossier);
        // }
        if (!Storage::disk('direct_public')->exists($dossier)) {
            Storage::disk('direct_public')->makeDirectory($dossier);
        }
        $validateDate = Validator::make($request->all(),
        [
            'libele'=>'required|string|max:255',
            'marque_id'=>'required|exists:marques,id',
            'description'=>'required|string|max:255',
            'prix'=>'required|string|max:255',
            'etat'=>'required|string|max:255',
            'image'=>($request->image!=null)?'file|mimes:jpg,jpeg,png,gift,jfif':''
        ]);

        if($validateDate->fails()){
            return back()->with('echec',$validateDate->errors());

        }

        $fichier = $request->file('image');
        $type =($request->file('image')!=null)? $fichier->getClientOriginalExtension():'';
        $depotId= $request->depot_id;
        $data = [
            'marque_id'=>$request->marque_id,
            'libele'=>$request->libele,
            'description'=>$request->description,
            'prix'=>$request->prix,
            'etat'=>$request->etat,
            'image'=>($request->file('image')!=null)? "$request->libele.$type":null,
        ];
// dd($data);
        $produit = null;
        $getProduit = Produit::where("libele",$request->libele)->where('etat',$request->etat)->first();
        if($getProduit === null){
            $produit = Produit::create($data);
        }
        // dd($produit);
        // if($request->file('image') != null){
        //     $fichier = $request->file('image')->storeAs($dossier,"$produit->libele.$type",'public');
        // }
         if ($request->hasFile('image')) {
                $fichier = $request->file('image')->storeAs(
                    $dossier,
                    "$produit->libele.$type",
                    'direct_public'
                );
        }
        if($produit !== null){

            if($request->quantite != null){
                $dataApro = [
                    'user_id'=>auth()->user()->id,
                    'depot_id'=>$depotId,
                    'produit_id'=>$produit->id,
                    'quantite'=>$request->quantite,
                    'confirm'=>false,
                    'receptionUser'=>null
                ];
                $dataProD =['depot_id'=>session('depot_id'),
                            'produit_id'=>$produit->id,
                            'quantite'=>($request->quantite!=null)?$request->quantite:0];
                $approvisionnement = Approvisionnement::create($dataApro);
                $produitDepot = ProduitDepot::create($dataProD);
            }else{
                return back()->with('success',"Enregistrement reussi sans approvisionnement !");
            }
            
            return back()->with('success','Enregistrement reussi avec succès plus approvisionnement !');
            
        }else{
            // dd('ok i$on verifie');
            $getDepotProduitExist =ProduitDepot::where('depot_id',$request->depot_id)->where('produit_id',$getProduit->id)->first();
            if($getDepotProduitExist == null){
                // dd('affection');
                $dataProD =['depot_id'=>session('depot_id'),
                                'produit_id'=>$getProduit->id,
                                'quantite'=>$request->quantite];
                if($request->quantite != null && $request->quantite >0){
                    $dataApro = [
                        'user_id'=>auth()->user()->id,
                        'depot_id'=>$depotId,
                        'produit_id'=>$getProduit->id,
                        'quantite'=>$request->quantite,
                        'confirm'=>false,
                        'receptionUser'=>null
                    ];
                    $approvisionnement = Approvisionnement::create($dataApro);
                    $produitDepot = ProduitDepot::create($dataProD);
                    return back()->with('success','Enregistrement reussi avec succès plus approvisionnement !');
                }else{
                    $produitDepot = ProduitDepot::create($dataProD);
                    return back()->with('success',"Enregistrement reussi sans approvisionnement !");
                }
            }
            return back()->with('echec',"Enregistrement n'a pas abouti, il est probable que ce produit existe déjà !");
        }
    }

    public function importProduitExcel(Request $request){
        // dd($request->all());
        $array = Excel::toArray(new ProduitImport, $request->file('prodExcel'));
        $modelExcel =  ["libele" => "",
                        "marque" => "",
                        "categorie" => "",
                        "quantite" => "",
                        "prix" => "",
                        "etat" =>"",
                        "description" => ""];
        $dataProduit= [
                    'marque_id'=>'',
                    'libele'=>'',
                    'description'=>'',
                    'prix'=>'nullable',
                    'etat'=>'',
                ];
       
        $dataCategorie = ['libele'=>''];
        $dataMarque = ['libele'=>'','categorie_id'=>''];
        $dataFormExcel = $array[0];
        $dataFormExcel = array_change_key_case($dataFormExcel, CASE_LOWER);
        if($dataFormExcel!=null){
            if (array_keys($modelExcel) === array_keys($dataFormExcel[0])) {
                foreach($dataFormExcel as $k=>$v){

                    $dataProduit['libele']=$v['libele'];
                    $dataProduit['description']=$v['description'];
                    $dataProduit['etat']=($v['categorie']=='ordinateur')?'Reconditionnée':'Neuf';
                    $dataProduit['prix']=$v['prix'];
                    $nomCategorie = $v['categorie'];
                    $nomMarque = $v['marque'];

                    $categorie = Categorie::where('libele', $nomCategorie)
                            ->with(['marque' => function ($query) use ($nomMarque) {
                                $query->where('libele', $nomMarque);
                            }])->first();

                    if (!$categorie) {
                        //on cree la categorie si non nulle
                        if($nomCategorie != null){
                            $dataCategorie['libele']=$nomCategorie;
                            $createNewCat = Categorie::firstOrcreate($dataCategorie);
                        }else{                           
                            $createNewCat = Categorie::firstOrcreate(['libele'=>'Divers']);
                        }
                        ($nomMarque != null)?$dataMarque['libele']=$nomMarque:$dataMarque['libele']='Divers';
                        
                        $dataMarque['categorie_id'] = $createNewCat->id;
                        $createNewMarque = Marque::firstOrcreate($dataMarque);
                        $dataProduit['marque_id'] = $createNewMarque->id;
                        
                    } elseif ($categorie->marque->isEmpty()) {
                        //on ajoute la marque si non null
                        ($nomMarque != null)?$dataMarque['libele']=$nomMarque:$dataMarque['libele']='Divers';
                        $dataMarque['categorie_id'] = $categorie->id;
                        $createNewMarque = Marque::firstOrcreate($dataMarque);
                        $dataProduit['marque_id']=$createNewMarque->id;
                    } else {
                        $marque = $categorie->marque->first(); 
                        $dataProduit['marque_id']=$marque->id;
                    }
                    $texte = $v['libele'];
                    $position = strpos($texte, 'HSN');
                    if ($position !== false) {
                        $avantHSN = substr($texte, 0, $position);
                        $dataProduit['libele']=$avantHSN; 
                    }
                    // dd($dataProduit, 'enregistrement', $v['quantite']);
                    if($v['libele']!=null){
                        $findProdExist = Produit::where('libele', $dataProduit['libele'])->where('etat', $dataProduit['etat'])->first();
                        ($findProdExist!=null)?$produitId=$findProdExist->id:$produitId = Produit::firstOrcreate($dataProduit)->id;
                        
                            if($v['quantite']!= null){
                                $dataApro = [
                                'user_id'=>auth()->user()->id,
                                'depot_id'=>$request->depot_id,
                                'produit_id'=>$produitId,
                                'quantite'=>$v['quantite'],
                                'confirm'=>false,
                                'receptionUser'=>null
                            ];
                        $approvisionnement = Approvisionnement::create($dataApro);
                            }
                        $dataProD = ['depot_id'=>$request->depot_id,
                                    'produit_id'=>$produitId,
                                    'quantite'=>$v['quantite']
                                ];
                        $produitDepot = ProduitDepot::create($dataProD);  
                    }
                    // dd('Enregistrement effectué');
                }
                return back()->with('success','Enregistrement reussi avec succès !');
            } else {
                return back()->with('echec',"La structure de votre tableau ne correspond pas à la stucture demandée !");
            }
        }
        return back()->with('echec','Importation échouée, votre fichier excel est vide');   
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $produit = Produit::where("id",$id)->get();
        return response()->json(['success'=>true, 'data'=>$produit]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Produit $produit)
    {
        return back()->with('success',"Bientôt disponible"); 
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
            'marque_id'=>$request->marque_id,
            'description'=>$request->description,
            'prix'=>$request->prix,
            'quatité'=>$request->quatité,
            'etat'=>$request->etat,
            'image'=>$request->image
        ];

        $produit = Produit::where('id',$id)->update($data);
        return response()->json(['success'=>true, 'data'=>Produit::find($id)]);
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $delete = Produit::where('id',$id)->delete();
        if(!$delete){
            return response()->json(['success'=>true, 'data'=>'echec de suppression']);
        }
        return response()->json(['success'=>true, 'data'=>'Suppression reussie!']);
    }
}
