<?php

namespace App\Http\Controllers;

use App\Models\Depot;
use App\Models\Produit;
use App\Models\Categorie;
use App\Models\ProduitDepot;
use Illuminate\Http\Request;
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
        if (!Storage::disk('public')->exists($dossier)) {
            Storage::disk('public')->makeDirectory($dossier);
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

        $data = [
            'marque_id'=>$request->marque_id,
            'libele'=>$request->libele,
            'description'=>$request->description,
            'prix'=>$request->prix,
            'etat'=>$request->etat,
            'image'=>($request->file('image')!=null)? "$request->libele.$type":null,
        ];
// dd($data);
        $produit = Produit::create($data);
        if($request->file('image') != null){
            $fichier = $request->file('image')->storeAs($dossier,"$produit->libele.$type",'public');
        }
        if($produit){

            if($request->quantite != null){
                $dataApro = [
                    'user_id'=>auth()->user()->id,
                    'depot_id'=>$request->depot_id,
                    'produit_id'=>$produit->id,
                    'quantite'=>$request->quantite,
                    'confirm'=>false,
                    'receptionUser'=>null
                ];
                $dataProD =['depot_id'=>$request->depot_id,
                            'produit_id'=>$produit->id,
                            'quantite'=>$request->quantite];
                $approvisionnement = Approvisionnement::create($dataApro);
                $produitDepot = ProduitDepot::create($dataProD);
            }else{
                return back()->with('success',"Enregistrement reussi sans approvisionnement !");
            }
            
            return back()->with('success','Enregistrement reussi avec succès plus approvisionnement !');
            
        }
        return back()->with('echec',"Enregistrement n'a pas abouti !");
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
