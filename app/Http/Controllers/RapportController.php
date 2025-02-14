<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use App\Models\Depot;
use App\Models\Vente;
use App\Models\Transfert;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Approvisionnement;
use Illuminate\Support\Facades\App;

class RapportController extends Controller
{
    public function journalier($depot){

        $today = Carbon::now()->format('Y-m-d');
        $depot = Depot::where('libele',$depot)->first();

        $approJour = Approvisionnement::orderBy('user_id')->where('depot_id', $depot->id)
                    ->where('created_at','%'.$today.'%')
                    ->get();
        $transJour = Transfert::orderBy('user_id')->where('depot_id', $depot->id)
                    ->where('created_at','%'.$today.'%')
                    ->get();
        $venteJour = Vente::orderBy('user_id')->where('depot_id', $depot->id)
                        ->where('created_at','%'.$today.'%')
                        ->get();
        
        $pdf = App::make('dompdf.wrapper');
        
        return view('rapport.journalier', compact ( "approJour", "transJour", "venteJour"));
        // $pdf = Pdf::loadView('rapport.journalier', $venteJour)
        // return $pdf->download('essaie rapport.pdf');
        // return $pdf->download('Rapport Test.pdf',$pdf->stream());
        // $pdf->loadHTML('<h1>Test Rapport jounalier vide</h1> <br/>'.$venteJour.'<br/>'.$transJour.'<br/>'.$approJour);
    }
    public function mensuel($depot){
        $mois = Carbon::now()->format('m');
        $depot = Depot::where('libele',$depot)->first();

        $approMois = Approvisionnement::orderBy('user_id')->where('depot_id', $depot->id)
                    ->whereMonth('created_at',$mois)
                    ->get();
        $transMois = Transfert::orderBy('user_id')->where('depot_id', $depot->id)
                    ->whereMonth('created_at',$mois)
                    ->get();
        $venteMois = Vente::orderBy('user_id')->where('depot_id', $depot->id)
                        ->whereMonth('created_at',$mois)
                        ->get();
        Carbon::setLocale('fr');
        
        $mois = Carbon::now()->isoFormat('MMMM YYYY');;
                        
        return view('rapport.mensuel', compact ( "approMois", "transMois", "venteMois","mois"));
    }
    public function annuel($depot){
        $year = Carbon::now()->format('Y');
        $depot = Depot::where('libele',$depot)->first();

        $approAn = Approvisionnement::orderBy('user_id')->where('depot_id', $depot->id)
                    ->whereYear('created_at',$year)
                    ->get();
        $transAn = Transfert::orderBy('user_id')->where('depot_id', $depot->id)
                    ->whereYear('created_at',$year)
                    ->get();
        $venteAn = Vente::orderBy('user_id')->where('depot_id', $depot->id)
                        ->whereYear('created_at',$year)
                        ->get();
        return view('rapport.annuel', compact ( "approAn", "transAn", "venteAn","year"));
    }
}
