<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Depot;
use App\Models\Categorie;
use App\Models\ProduitDepot;
use Illuminate\Http\Request;
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
        $prodDepot = ProduitDepot::where("depot_id",$id)->with('produit')->get();
        return view('depot.show',compact('prodDepot','depot','user','cat'));
    }
    public function showProduit(string $depot)
    {
        $depotData = Depot::where("libele",$depot)->first();
        $user = Auth::user();
        $prodDepot = ProduitDepot::where("depot_id",$depotData->id)->with('produit')->latest()->get();
        return view('depot.produit',compact('prodDepot','depotData','user'));
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
