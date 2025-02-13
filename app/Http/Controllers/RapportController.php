<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Depot;
use App\Models\Vente;
use App\Models\Transfert;
use Illuminate\Http\Request;
use App\Models\Approvisionnement;

class RapportController extends Controller
{
    public function journalier($depot){

        $today = Carbon::now()->format('Y-m-d');
        $depot = Depot::where('libele',$depot)->first();

        $approJour = Approvisionnement::where('depot_id', $depot->id)
                    ->where('created_at','like','like'.$today.'like')
                    ->get();
        $transJour = Transfert::where('depot_id', $depot->id)
                    ->where('created_at','like','like'.$today.'like')
                    ->get();
        $venteJour = Vente::where('depot_id', $depot->id)
                        ->where('created_at','like','like'.$today.'like')
                        ->get();
        
        return [$approJour, $transJour, $venteJour];
    }
    public function mensuel($depot){
        return back()->with('success',"Bientôt disponible");
    }
    public function annuel($depot){
        return back()->with('success',"Bientôt disponible");
    }
}
