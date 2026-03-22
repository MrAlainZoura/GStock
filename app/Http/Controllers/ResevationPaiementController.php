<?php

namespace App\Http\Controllers;

use App\Models\ReservationPaiement;
use App\Models\ResevationPaiement;
use Illuminate\Http\Request;

class ResevationPaiementController extends Controller
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
    public function store(Request $request, $reservation)
    {
        $id = $reservation/89;
        $getReservation = ReservationPaiement::where('reservation_id', $id)->latest()->first();
        if(!$getReservation){
            return back()->with('echec', "Impossible de trouver cette reservation");
        }
        $cdfPrime = $getReservation->reservation->depot->use_cdf;
        $taux = $getReservation->reservation->updateTaux;
        $solde = $getReservation->solde;
        $versement = ($cdfPrime) 
            ? $request->paiment 
            : $request->paiment * $taux;
        $newSolde = $getReservation->solde - $versement;
        $data = [
            "reservation_id"=>$id,
            "tranche"=>$getReservation->tranche+1,
            "avance"=>$versement,
            "solde"=>$newSolde,
            "net"=>$getReservation->net,
            "completed"=>($newSolde==0)?true:false
        ];

        if($versement <= $solde){
            $createPaie= ReservationPaiement::create($data);
            $routeParam = 56*$createPaie->reservation_id;
            return to_route("reservation.show", $routeParam);
        }
        return back()->with('echec',"Une erreur s'est produite");    
    }

    /**
     * Display the specified resource.
     */
    public function show(ResevationPaiement $resevationPaiement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ResevationPaiement $resevationPaiement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ResevationPaiement $resevationPaiement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ResevationPaiement $resevationPaiement)
    {
        //
    }
}
