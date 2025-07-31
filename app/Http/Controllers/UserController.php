<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Depot;
use App\Models\UserRole;
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
        if(Auth::user()->user_role->role->libele =='Administrateur' || Auth::user()->user_role->role->libele=='Super admin'){
            $user = User::whereHas('depotUser.depot', function ($query) {
                    $query->where('libele', session('depot'));
                })->with(['depotUser.depot'])->get();
            $user->prepend(Auth::user()); //ajoute admin au debut
        }else{
            $user[] = Auth::user();
        }
        return view('users.index', compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if(Auth::user()->user_role->role->libele=="Administrateur"){
            $depot = Depot::orderBy('libele')->where('user_id', Auth::user()->id)->get();
        }
        if(Auth::user()->user_role->role->libele == 'Super admin'){
            $depot = Depot::orderBy('libele')->get();
        }
       $roleAutorises = ['Administrateur', 'Super admin'];
        if (!in_array(Auth::user()->user_role->role->libele, $roleAutorises)) {
            // dd('Accès refusé', Auth::user()->user_role->role->libele);
            return back()->with('echec', 'Vous ne disposez pas de droit nécessaire pour effectuer cette action !');
        }
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
            'image'=>'file|mimes:.jpg,jpeg,png,gift,jfif'
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
            'postnom'=>$request->postnom,
            'prenom'=>$request->prenom,
            'image'=>($request->file('image')!=null)? "$request->name$request->postnom.$type":null,
        ];
        $user = User::create($data);
        if($user){
            $getUSerRoleId = Role::where('libele', 'user')->first();

            $dataRoleUser = ['user_id'=>$user->id, 'role_id'=>$getUSerRoleId->id];
            $createRoleUSer = UserRole::create($dataRoleUser);

            $depotUser = DepotUser::create(['depot_id'=>$request->depot_id,'user_id'=>$user->id]);
            $dossier = 'users';
            if (!Storage::disk('direct_public')->exists($dossier)) {
                Storage::disk('direct_public')->makeDirectory($dossier);
            }

            if ($request->hasFile('image')) {
                $fichier = $request->file('image')->storeAs(
                    $dossier,
                    "$user->name$user->postnom.$type",
                    'direct_public'
                );
            }
        /*if (!Storage::disk('public')->exists($dossier)) {
            Storage::disk('public')->makeDirectory($dossier);
        }
        if($request->file('image') != null){
            $fichier = $request->file('image')->storeAs($dossier,"$user->name$user->postnom.$type",'public');
        }*/
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
        $id = (int)$userShow/6789012345;
        // $id = "";
        // $name="";
        // $parts = explode(" ", $string);
        // if (count($parts) == 2) {
        //     $name = $parts[0];
        //     $id = $parts[1]/6789012345;
        // }
            $user= User::where("id","=", $id)->first();
            if($user){
                return back()->with('success',"Voir plus, Bientot disponible !");
            }
            return back()->with('echec',value: "Utilisateur introuvable !");
        }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $userEdit)
    {
        // dd($userEdit);
        // [$name, $id] = [
        //     preg_replace('/[0-9]+/', '', $userEdit),
        //     preg_replace('/[^0-9]/', '', $userEdit)
        // ];
        $id = (int)$userEdit;
        $roleAutorises = ['Administrateur', 'Super admin'];
        if (!in_array(Auth::user()->user_role->role->libele, $roleAutorises)) {
            $depot[]=Auth::user()->depotUser[0]->depot;
            // dd('user', $depot);
        }else{
            $depot = Auth::user()->depot;
            if(Auth::user()->user_role->role->libele == $roleAutorises[1]){
                $adminDepot = Depot::where('libele', session('depot'))->where('id', session('depot_id'))->first();
                $depot = $adminDepot->user->depot;
            }
        }
        if (!empty($id)) {
            $user_id = $id/6789012345;
            $user = User::where("id","=", $user_id)->with('depotUser')->first();
            // dd($user, $depot);
            $tabAffectation =[];
            if($user){
                if(count($user->depotUser) > 0){
                    foreach ( $user->depotUser as $k=>$v)
                    {
                        $tabAffectation [] = $v->depot->libele;
                    }
                }
                return view('users.profil',compact('user','depot', 'tabAffectation'));
            }
            return back()->with('echec',"Une erreur inattendue, utilisateur introuvable s'est produite reessayer plus tard");
        }else{
            return back()->with('echec',"Une erreur inattendue de format s'est produite reessayer plus tard");
        }
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
            'image'=>'file|mimes:jpg,jpeg,png,gift,jfif',
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
            Storage::move("public/uploads/$dossier/$findUser->image", "public/uploads/$dossier/$image");
        }elseif($findUser->name!= null || $findUser->postnom != null){
            $image = ($request->file('image')!=null)? "$request->name$request->postnom.$type":$findUser->image;
        }
        if($request->file("image")!=null) {
            // Storage::delete("public/$dossier/$findUser->image");
            $fichier = $request->file('image')->storeAs($dossier,$image,'direct_public');
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
        $userUpdate = User::where('id', $id)->first();
    
        if(is_array($request->affectation) && count($request->affectation) > 0){
            $tableauAffectation = $request->affectation;
            $affectationSize = count($request->affectation);
            $depotUserSize = count( $userUpdate->depotUser);
            if($affectationSize == $depotUserSize)
            {
                foreach($userUpdate->depotUser as $key=>$depotUser){
                    $depotUser->depot_id = $tableauAffectation[$key];
                    $depotUser->save();
                }
            }elseif($affectationSize > $depotUserSize){
                // dd('on fait la mise à jour de ce qui existe et on ajoute'); 
                foreach ($tableauAffectation as $key => $affectation) {
                    if (isset($userUpdate->depotUser[$key])) {
                        $userUpdate->depotUser[$key]->update(['depot_id' => $affectation]);
                    } else {
                        DepotUser::firstOrCreate([
                            'depot_id' => $affectation,
                            'user_id' => $userUpdate->id
                        ]);
                    }
                }
            }elseif($affectationSize < $depotUserSize){
                // dd('on fait la mise à jour de ce qui existe et on supprime'); 
                foreach($userUpdate->depotUser as $key=>$depotUser){
                    if(isset($tableauAffectation[$key])){
                        $depotUser->depot_id = $tableauAffectation[$key];
                        $depotUser->save();
                    }else{
                        $depotUser->delete();
                    }
                }   
            }

        }
        if($userUpdate->update($data2)) {
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
        $dossier="users";
        $id = (int) $userDelete/6789012345;
        // $name="";
        // $parts = explode(" ", $string);
        // if (count($parts) == 2) {
        //     $name = $parts[0];
        //     $id = $parts[1]/6789012345;
        // }
        $user= User::where("id", $id)->first();
        if($user != null){
            $image=$user->image;
            if($user->delete()){
                if (Storage::exists("public/uploads/$dossier/$image")) {
                    Storage::delete("public/uploads/$dossier/$image");
                }
                return back()->with('success',"Utilisateur supprimé avec succès !");
            }
            return back()->with('echec',"Erreur inattendue, utilisateur est lié à un processus encours!");
        }
        return back()->with('echec',"Erreur inattendue !");
    }

    public function login(Request $request){
        $daliation = Validator::make($request->all(),[
            'email'=>'required',
            'password'=>'required|min:4'
        ]);
        if($daliation->fails()){
            return back()->with('echec',$daliation->errors());
        }
        $data = ['email'=>$request->email, 'password'=>$request->password];
        $dataLoginByName = ['name'=>$request->email, 'password'=>$request->password];
        if(Auth::attempt($data) || Auth::attempt($dataLoginByName)){
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

    public function createCompte(Request $request){
        $validateDate = Validator::make($request->all(),
        [
            'name'=>'required',
            'email'=>'required|email|max:255|unique:users',
            'password'=>'required|min:4'
        ]);

        if($validateDate->fails()){
            $message = $validateDate->errors();
            $erreursEmail = $validateDate->errors()->get('email');
            if($erreursEmail!=null){
                return back()->with('echecRegister','Ce email est déjà pris ou incorrect');
            }
            return back()->with('echecRegister','Erreur inattendue, Veuillez réessayer.');   
        }
        $data = [
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password)
        ];

        // dd($data);
        $createAdmin = User::create($data);
        if($createAdmin){ 
            $getAdminRoleId = Role::where('libele', 'Administrateur')->first();
            if($getAdminRoleId !== null){
                $roleId=$getAdminRoleId->id;
            }else{
                $dataAllRole = [
                    'Super admin',
                    'Administrateur',
                    'user'
                ];
                foreach($dataAllRole as $role){
                   $createAdminRole = Role::firstOrCreate(['libele'=>$role]);
                   ($role == 'Administrateur')?$roleId =$createAdminRole->id:"";
                }
            }
            $dataRoleUser = ['user_id'=>$createAdmin->id, 'role_id'=>$roleId];
            $createRoleUSer = UserRole::create($dataRoleUser);
        }
       return back()->with('succes', 'Veuillez vous connectez à présent');
    }
}
