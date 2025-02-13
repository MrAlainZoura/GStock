<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
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
        
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML('<h1>Test vide</h1>');

        return [$approJour, $transJour, $venteJour];
        // return $pdf->download('Teste.pdf',$pdf->stream());

        // $pdf = Pdf::loadView('pdf.invoice', $data);
        // return $pdf->download('invoice.pdf');
    }
    public function mensuel($depot){
        return back()->with('success',"Bientôt disponible");
    }
    public function annuel($depot){
        return back()->with('success',"Bientôt disponible");
    }
}
