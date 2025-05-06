<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Illuminate\Http\Request;
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
            'marque_id'=>'required|exists:marques,id',
            'description'=>'required|string|max:255',
            'prix'=>'required|string|max:255',
            'quatité'=>'required|int|max:255',
            'etat'=>'required|string|max:255',
            'image'=>'file|mimes:jpg, jpeg, png, gift'
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

        $produit = Produit::create($data);
        return response()->json(['success'=>true, 'data'=>$produit]);
   
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
