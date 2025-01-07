<?php

namespace App\Http\Controllers;

use App\Models\Approvisionnement;
use Illuminate\Http\Request;

class ApprovisionnementController extends Controller
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
    public function showDepotAppro($depot_id)
    {
        dd($depot_id);
        $appro=Approvisionnement::where("depot_id",$depot_id)->get();
        return view("appro.index",compact("appro"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Approvisionnement $approvisionnement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Approvisionnement $approvisionnement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Approvisionnement $approvisionnement)
    {
        //
    }
}
