<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Abonnement;
use App\Models\Souscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbonnementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $user = User::find((int)$id/13);
        if (!$user) {
            return back()->with('echec', 'erreur utilisateur non trouvé');
        }
        $roleAutorises = ['Super admin'];
        if (in_array(Auth::user()->user_role->role->libele, $roleAutorises)) {
            $abonnements = Souscription::latest()->get();
        }else{
            $abonnements = Souscription::where('user_id', $user->id)->latest()->get();
        }
        return view("abonnement.index", compact("abonnements"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $abonnement = Abonnement::all();
        return view('abonnement.create', compact('abonnement'));
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
    public function show(Abonnement $abonnement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Abonnement $abonnement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Abonnement $abonnement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Abonnement $abonnement)
    {
        //
    }
}
