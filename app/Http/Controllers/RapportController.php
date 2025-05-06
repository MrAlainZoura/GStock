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
                    ->where('created_at','like','%'.$today.'%')
                    ->get();
        $transJour = Transfert::orderBy('user_id')->where('depot_id', $depot->id)
                    ->where('created_at','like','%'.$today.'%')
                    ->get();
        $venteJour = Vente::orderBy('user_id')->where('depot_id', $depot->id)
                        ->where('created_at','like','%'.$today.'%')
                        ->get();
        
        return view('rapport.journalier', compact ( "approJour", "transJour", "venteJour"));
    }
    public function mensuel($depot){
        Carbon::setLocale('fr');
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


    public function journalieru($depot){
        Carbon::setLocale('fr');

        $today = Carbon::now()->format('Y-m-d');
        $depot = Depot::where('libele',$depot)->first();

        $approJour = Approvisionnement::orderBy('user_id')->where('depot_id', $depot->id)
                    // ->where('created_at','%'.$today.'%')
                    ->get();
        $transJour = Transfert::orderBy('user_id')->where('depot_id', $depot->id)
                    // ->where('created_at','%'.$today.'%')
                    ->get();
        $venteJour = Vente::orderBy('user_id')->where('depot_id', $depot->id)
                        // ->where('created_at','%'.$today.'%')
                        ->get();
                        
        $jour = Carbon::now()->isoFormat('dddd D MMMM YYYY');
        $data =[
            'vente'=>$venteJour,
            'appro'=>$approJour,
            'trans'=>$transJour,
            'date'=>$jour
        ];
        $pdf = Pdf::loadView('pdf.rapportJ', $data);
        return $pdf->download('invoice.pdf');
    }
    public function facture($vente){
       
        $id= $vente/56745264509;
        $findVenteDetail = Vente::where('id',$id)->first();
        // dd($detailVente->venteProduit[0]->produit->image);
        // return view('vente.fact', compact('findVenteDetail')) ;
        
        $data =[
            'findVenteDetail'=>$findVenteDetail, 
        ];
        $code = str_replace('/', '-', $findVenteDetail->code);
        $facture ="facture ".$findVenteDetail->user->name." ".$findVenteDetail->created_at." ".$code .".pdf";
      
        $customPaper = array(0, 0, 278, 600); // (gauche, haut, droite, bas)
        $pdf = Pdf::loadView('vente.fact', $data);
        $pdf->setPaper($customPaper);
        return $pdf->download($facture);
    }

    
}
