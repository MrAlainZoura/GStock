<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;

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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
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
