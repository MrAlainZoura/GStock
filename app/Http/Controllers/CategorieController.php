<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Marque;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class CategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cat = Categorie::latest()->get();
        return response()->json(['success'=>true, 'data'=>$cat]);
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
            'user_id'=>'required|string|max:255',
            'image'=>'required|file|mimes:jpg, jpeg, png, gift, jfif',
            'marque'=>'array',
        ]);

        if($validateDate->fails()){
            return back()->with('echec',$validateDate->errors());

        }
        $dossier = 'cat';
        // Vérifier si le dossier existe, sinon le créer
        if (!Storage::disk('public')->exists($dossier)) {
            Storage::disk('public')->makeDirectory($dossier);
        }
        $fichier = $request->file('image');
        $type = $fichier->getClientOriginalExtension();
        $data = [
            'libele'=>$request->libele,
            'image'=>($request->file('image')!=null)? "$request->libele.$type":null
        ];

        $cat = Categorie::create($data);
        if($cat){
            if($request->file('image') != null){
                $fichier = $request->file('image')->storeAs($dossier,"$cat->libele.$type",'public');
            }
           foreach($request->marque as $k=>$v){
                if($v!=null)
                {
                    $marque = Marque::create(['libele'=>$v,'categorie_id'=>$cat->id]);
                }  
           }
           return back()->with('success',"success enregistrment");
        }
        return back()->with('echec',"Enregistrement n'a pas abouti");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $cat = Categorie::where("id",$id)->get();
        return response()->json(['success'=>true, 'data'=>$cat]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Categorie $categorie)
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

        $cat = Categorie::where('id',$id)->update($data);
        return response()->json(['success'=>true, 'data'=>Categorie::find($id)]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $delete = Categorie::where('id',$id)->delete();
        if(!$delete){
            return response()->json(['success'=>true, 'data'=>'echec de suppression']);
        }
        return response()->json(['success'=>true, 'data'=>'Suppression reussie!']);
    }
}
