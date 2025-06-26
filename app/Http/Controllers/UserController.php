<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Depot;
use App\Models\DepotUser;
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
        $user = User::with('depotUser.depot')->get();
        // dd((count($user[0]->depotUser)<1)?'ok':$user[0]->depotUser);//->depotUser[0]->depot->libele);
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
        dd($request->all());
        $validateDate = Validator::make($request->all(),
        [
            'name'=>'required',
            'email'=>'required|email|max:255|unique:users',
            'genre'=>($request->genre)?'string':'',
            'naissance'=>($request->naissance)?'string':'',
            'fonction'=>($request->fonction)?'string':'',
            'niveauEtude'=>($request->niveauEtude)?'string':'',
            'option'=>($request->option)?'string':'',
            'adresse'=>($request->adresse)?'string':'',
            'tel'=>($request->tel)?'string':'',
            'depot_id'=>'required|exists:depots,id',
            'postnom'=>($request->postnom)?'string':'',
            'prenom'=>($request->prenom)?'string':'',
            'image'=>'file|mimes:jpg,jpeg,png,gift,jfif'
        ]);

        if($validateDate->fails()){
            return back()->with('echec',$validateDate->errors());
        }
        $fichier = $request->file('image');
        $type = ($request->file('image')!=null)?$fichier->getClientOriginalExtension():null;
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
            // 'depot_id'=>$request->depot_id,
            'postnom'=>$request->postnom,
            'prenom'=>$request->prenom,
            'image'=>($request->file('image')!=null)? "$request->name$request->postnom.$type":null,
        ];
        $user = User::create($data);
        if($user){
            $depotUser = DepotUser::create(['depot_id'=>$request->depot_id,'user_id'=>$user->id]);
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

    public function loginCreate(){
        return view('users.login');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $userShow)
    {
        $string = $userShow;
        $id = "";
        $name="";
        $parts = explode(" ", $string);
        if (count($parts) == 2) {
            $name = $parts[0];
            $id = $parts[1]/6789012345;
        }
                $user= User::where("id","=", $id)->where('name',$name)->first();
                return back()->with('success',"Voir plus, Bientot disponible !");
            }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $userEdit)
    {
        $string = $userEdit;
        $id = "";
        $name="";
        $parts = explode(" ", $string);
        if (count($parts) == 2) {
            $name = $parts[0];
            $id = $parts[1]/6789012345;
        }
        $depot = Depot::orderBy('libele')->get();
        $user= User::where("id","=", $id)->where('name',$name)->with('depotUser')->first();
        return view('users.profil',compact('user','depot'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $user)
    {
        $validateDate = Validator::make($request->all(),
        [
            'name'=>'required',
            'email'=>'required|email|max:255',
            'image'=>'file|mimes:jpg, jpeg, png, gift, jfif',
            'genre'=>($request->genre)?'string':'',
            'naissance'=>($request->naissance)?'string':'',
            'fonction'=>($request->fonction)?'string':'',
            'niveauEtude'=>($request->niveauEtude)?'string':'',
            'option'=>($request->option)?'string':'',
            'adresse'=>($request->adresse)?'string':'',
            'tel'=>($request->tel)?'string':'',
            'postnom'=>($request->postnom)?'string':'',
            'prenom'=>($request->prenom)?'string':'',
        ]);

        if($validateDate->fails()){
            return back()->with('echec',$validateDate->errors());
        }
        $id =$user/652062511003;
        $findUser = User::where('id', $id)->first();
        $image = $findUser->image;

        $dossier="users";
        $fichier = $request->file('image');
        $type = ($request->file('image')!=null)?$fichier->getClientOriginalExtension():null;

        if($findUser->name!= $request->name || $request->postnom != $findUser->postnom) {
            $image=($request->file('image')!=null)? "$request->name$request->postnom.$type":$findUser->image;
            Storage::move("public/$dossier/$findUser->image", "public/$dossier/$image");
        }
        if($request->file("image")!=null) {
            Storage::delete("public/$dossier/$findUser->image");
            $fichier = $request->file('image')->storeAs($dossier,$image,'public');
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
            'tel'=>$request->tel,
            'postnom'=>$request->postnom,
            'prenom'=>$request->prenom,
            'image'=>$image,
        ];

        $data2 = array_filter($data, function($val){return !is_null($val);});
        $userUpdate = User::where('id', $id)->update($data2);
        if($request->depot_id!=null){
            $update_affectation = DepotUser::where('user_id',$id)->update(['depot_id'=>$request->depot_id]);
        }
        if($userUpdate) {
            return back()->with('success',"Profil mis à jour avec succès !");
        }
        return back()->with('echec',"Aucune modification apportée à ce profil !");
    }
    public function updataPass(Request $request, $user){
        $id = $user/652062511003;
        $getPassword = User::where("id", $id)->first();
        if (Hash::check($request->holdPass,  $getPassword->password)){
            $updatePass = $getPassword->update(["password"=>Hash::make($request->password)]);
            if($updatePass){
                return back()->with('success',"Mot de passe mis à jour avec succès !");
            }
        }
        return back()->with('echec',"Ancien mot de passe incorrect !");
    }
    public function resetPass($user){
        $id =$user/652062511003;
        $getPassword = User::where("id", $id)->first();
        $updatePass = $getPassword->update(["password"=>Hash::make('0000')]);
        if($updatePass){
            return back()->with('success',"Mot de passe reinitialisé avec succès !");
        }
        
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $userDelete)
    {
        $string = $userDelete;
        $id = "";
        $name="";
        $parts = explode(" ", $string);
        if (count($parts) == 2) {
            $name = $parts[0];
            $id = $parts[1]/6789012345;
        }
        $user= User::where("id","=", $id)->where('name',$name)->first();
        return back()->with('success',"Bientot disponible !");

        // $delete = User::where('id',$id)->delete();
        // if(!$delete){
        //     return response()->json(['success'=>true, 'data'=>'echec de suppression']);
        // }
        // return response()->json(['success'=>true, 'data'=>'Suppression reussie!']);
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
