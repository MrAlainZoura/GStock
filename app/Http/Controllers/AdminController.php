<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $getAdminAll = User::whereHas('user_role', function($query){
            $query->whereHas('role', function($role){
                $role->where('libele','Administrateur');
            });
        })->with("depot")->get();

        return view("admin.index", compact("getAdminAll"));

        //return response()->json($getAdminAll);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("admin.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $validateDate = Validator::make($request->all(),
        [
            'name'=>'required',
            'email'=>'required|email|max:255|unique:users'
        ]) ;

        if($validateDate->fails()){
            return back()->with('echec',$validateDate->errors());
        }
        $data = [
            'name'=>$request->name,
            'email'=>$request->email,
            'prenom'=>$request->prenom,
            'tel'=>$request->tel,
            'password'=>Hash::make('0000')
        ];
        $user = User::create($data);
        if($user){
            $getUSerRoleId = Role::where('libele', 'Administrateur')->first();
            $dataRoleUser = ['user_id'=>$user->id, 'role_id'=>$getUSerRoleId->id];
            $createRoleUSer = UserRole::create($dataRoleUser);
            if(!$createRoleUSer){
                return back()->with('echec','une erreur s est produite reessayez avec le role de user');
            }
            return back()->with('success','Enregistrement reussit');
        }
        return back()->with('echec','une erreur s est produite reessayez');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $id=$id/12;
        $roleAutorises = ['Administrateur', 'Super admin'];
        if (!in_array(Auth::user()->user_role->role->libele, $roleAutorises)) {
          return back()->with('echec',"Vous n'avez pas accès à cette information");
        }
        $user = User::find($id);
        $role = Role::latest()->get();
        if($user){
            return view("admin.edit", compact("role","user"));
        }
        return back()->with("echec","Pas d'information votre demande!");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $user)
    {
         $id =$user/14;
        $findUser = User::where('id', $id)->first();
        if(!$findUser){
            return back()->with('echec',"Utilisateur introuvable");
        }
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
        
        if($findUser->update($data2)) {
            return back()->with('success',"Profil mis à jour avec succès !");
        }
        return back()->with('echec',"Aucune modification apportée à ce profil !");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function confirmDeleteItem($message, $id, $route)
    {
        $action =Crypt::decrypt($route);
        $item = $message;
        $parametre = $id; 
        return view('comfirmDelete',compact('action','parametre','item'));
    }

}
