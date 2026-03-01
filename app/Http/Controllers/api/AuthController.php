<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;


class AuthController extends Controller
{
    /**
     * Display a listing of relation.
     */
    protected $relation = [
        'depot',
        'depotUser.depot',
        'souscription',
        'user_role'
    ];
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        //
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

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required'],
        ]);
        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return response()->json([
                'message' => 'Email ou mot de passe incorrect'
            ], 401);
        }
        // $user = Auth::guard('api')->user();
        $user = Auth::guard('api')->user()->load($this->relation);

        return response()->json([
            'user' => $user,
            'token' => $token,
            // 'role_user'=>$user->user_role->role,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ]);

    }
    public function me()
    {
        $user = auth('api')->user()->load($this->relation);
;
        return response()->json(
            [
                "success"=>true, 
                "data"=>$user,
                ]); 
    }

      //  Logout 
    public function logout()
    {
        auth('api')->logout();
        return response()->json(["success"=>true, 'message' => 'Déconnexion réussie']);
    }

    //  Refresh : génère un nouveau token
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    //  Méthode utilitaire pour formater la réponse
    protected function respondWithToken($token)
    {
        return response()->json([
            "success"=>true, 
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth('api')->factory()->getTTL() * 60
        ]);
    }

}
