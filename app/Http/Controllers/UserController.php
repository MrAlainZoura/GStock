<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Depot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::orderBy("depot_id")->with('depot')->get();
        return view('users.index', compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $depot = Depot::orderBy('libele')->get();
        return view('users.create',compact('depot'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validateDate = Validator::make($request->all(),
        [
            'name'=>'required',
            'email'=>'required|email|max:255|unique:users',
            'genre'=>'string',
            'naissance'=>'string',
            'fonction'=>'string',
            'niveauEtude'=>'string',
            'option'=>'string',
            'adresse'=>'string',
            'tel'=>'string',
            'depot_id'=>'required|exists:depots,id',
            'postnom'=>'string',
            'prenom'=>'string',
            'image'=>'file|mimes:jpg, jpeg, png, gift, jfif'
        ]);

        if($validateDate->fails()){
            return back()->with('echec',$validateDate->errors());
        }
        $fichier = $request->file('image');
        $type = $fichier->getClientOriginalExtension();
        $data = [
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make('0000'),
            'genre'=>$request->genre,
            'naissance'=>$request->naissance,
            'fonction'=>$request->fonction,
            'niveauEtude'=>$request->niveauEtude,
            'option'=>$request->option,
            'adresse'=>$request->adresse,
            'tel'=>$request->tel,
            'depot_id'=>$request->depot_id,
            'postnom'=>$request->postnom,
            'prenom'=>$request->teprenoml,
            'image'=>($request->file('image')!=null)? "$request->name$request->postnom.$type":null,
        ];

        $user = User::create($data);
        if($user){
            $dossier = 'users';
        if (!Storage::disk('public')->exists($dossier)) {
            Storage::disk('public')->makeDirectory($dossier);
        }if($request->file('image') != null){
            $fichier = $request->file('image')->storeAs($dossier,"$user->name$user->postnom.$type",'public');
        }
        return back()->with('success',"Enregistrement de $user->name $user->postnom a reussi !");
        }
        return back()->with('echec',"Une erreur inattendue s'est produite reessayer plus tard");
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
            // return to_route('dashboard'); 
            return redirect()->intended('dashbord');
        }
       return back()->with('echec','email ou mot de passe incorrect');
    }
    public function logout(){
        Auth::logout();
        return redirect(url("/"));
    }
}
