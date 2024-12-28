<?php

namespace App\Http\Controllers;

use App\Models\Marque;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MarqueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $marque = Marque::latest()->get();
        return response()->json(['success'=>true, 'data'=>$marque]);
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
            'categorie_id'=>'required|exists:categories,id',
        ]);

        if($validateDate->fails()){
            return $validateDate->errors();
        }
        $data = [
            'libele'=>$request->libele,
        ];

        $marque = Marque::create($data);
        return response()->json(['success'=>true, 'data'=>$marque]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $marque = Marque::where("id",$id)->get();
        return response()->json(['success'=>true, 'data'=>$marque]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Marque $marque)
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

        $marque = Marque::where('id',$id)->update($data);
        return response()->json(['success'=>true, 'data'=>Marque::find($id)]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $delete = Marque::where('id',$id)->delete();
        if(!$delete){
            return response()->json(['success'=>true, 'data'=>'echec de suppression']);
        }
        return response()->json(['success'=>true, 'data'=>'Suppression reussie!']);
    }
}
