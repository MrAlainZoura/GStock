<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::latest()->get();
        return response()->json(['success'=>true, 'data'=>$user]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
      
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateDate = Validator::make($request->all(),
        [
            'name'=>'required',
            'email'=>'required|email|max:255|unique:users',
            'password'=>'required',
            'genre'=>'string',
            'naissance'=>'string',
            'fonction'=>'string',
            'niveauEtude'=>'string',
            'option'=>'string',
            'adresse'=>'string',
            'tel'=>'string'
        ]);

        if($validateDate->fails()){
            return $validateDate->errors();
        }
        $data = [
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'genre'=>$request->genre,
            'naissance'=>$request->naissance,
            'fonction'=>$request->fonction,
            'niveauEtude'=>$request->niveauEtude,
            'option'=>$request->option,
            'adresse'=>$request->adresse,
            'tel'=>$request->tel
        ];

        $user = User::create($data);
        return response()->json(['success'=>true, 'data'=>$user]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::where("id",$id)->get();
        return response()->json(['success'=>true, 'data'=>$user]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
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
            'name'=>'required',
            'email'=>'required|email|max:255',
            'genre'=>'string',
            'naissance'=>'string',
            'fonction'=>'string',
            'niveauEtude'=>'string',
            'option'=>'string',
            'adresse'=>'string',
            'tel'=>'string',
            'id'=>'required'
        ]);

        if($validateDate->fails()){
            return $validateDate->errors();
        }
        $data = [
            'name'=>$request->name,
            'email'=>$request->email,
            'genre'=>$request->genre,
            'naissance'=>$request->naissance,
            'fonction'=>$request->fonction,
            'niveauEtude'=>$request->niveauEtude,
            'option'=>$request->option,
            'adresse'=>$request->adresse,
            'tel'=>$request->tel
        ];

        $data2 = array_filter($data, function($val){return !is_null($val);});
        $user = User::where('id', $id)->update($data2);
        return response()->json(['success'=>true, 'data'=>User::find($id)]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $delete = User::where('id',$id)->delete();
        if(!$delete){
            return response()->json(['success'=>true, 'data'=>'echec de suppression']);
        }
        return response()->json(['success'=>true, 'data'=>'Suppression reussie!']);
    }

    public function login(Request $request){
        $daliation = Validator::make($request->all(),[
            'email'=>'required|email',
            'password'=>'required|min:4'
        ]);
        if($daliation->fails()){
            return back()->with('echec',$daliation->errors());
        }
        $data = ['email'=>$request->email, 'password'=>$request->password];
        if(Auth::attempt($data)){
            $request->session()->regenerate();
            return to_route('dashboard'); 
        }
       return back()->with('echec','email ou mot de passe incorrect');
    }
    public function logout(){
        Auth::logout();
        return redirect(url("/"));
    }
}
