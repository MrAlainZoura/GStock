<?php

namespace App\Http\Controllers;

use App\Models\Devise;
use Illuminate\Http\Request;

class DeviseController extends Controller
{
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
    public function show(Devise $devise)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Devise $devise)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $depot, $devise)
    {
        $valeur = $request->taux;
        $deviseId = $devise/145;


        if (strpos($valeur, ',') !== false) {
            $valeur = str_replace(',', '.', $valeur);
        } elseif (strpos($valeur, '.') !== false) {
        } else {
            if (!ctype_digit($valeur)) {
                return back()->with("echec","La valeur de taux invalide");
            }
        }
        $getDeviseToUpdate = Devise::find($deviseId);
        if($getDeviseToUpdate){
            $dataUpdate = [
                "libele"=> $request->devise,
                "taux"=> $valeur
            ];

            if($getDeviseToUpdate->update($dataUpdate)){
                return back()->with("success","La devise a été mise à jour avec succès !");
            }
                return back()->with("success","Aucune modification n'a été apportée !");
        }else{
                return back()->with("echec","La devise introuvable");
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Devise $devise)
    {
        //
    }
}
