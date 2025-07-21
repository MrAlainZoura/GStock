<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Dompdf\Dompdf;
use App\Models\Depot;
use App\Models\Vente;
use App\Models\Transfert;
use Carbon\CarbonInterval;
use App\Models\ProduitDepot;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Approvisionnement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

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
                //resume stock chaque produit
        $prodArrayResume = [];
        $allProdDepot = ProduitDepot::where('depot_id', $depot->id)->with('produit','produit.marque', 'produit.marque.categorie')->get();
        foreach($allProdDepot as $ke=>$val){
            $singleProdApro = Approvisionnement::where('depot_id', $depot->id)
                            ->where('produit_id',$val->produit_id)
                            ->where('created_at','like','%'.$today.'%')
                            ->sum('quantite');

            $singleProdVente =DB::table('ventes')
                            ->join('vente_produits', 'ventes.id', '=', 'vente_produits.vente_id')
                            ->where('ventes.created_at','like','%'.$today.'%')
                            ->where('produit_id',$val->produit_id)
                            ->where('depot_id', $depot->id)
                            ->sum('vente_produits.quantite');
            $singleProdTrans =DB::table('transferts')
                            ->join('produit_transferts', 'transferts.id', '=', 'produit_transferts.transfert_id')
                            ->where('transferts.created_at','like','%'.$today.'%')
                            ->where('produit_id',$val->produit_id)
                            ->where('depot_id', $depot->id)
                            ->sum('produit_transferts.quantite');

            $singleProdResume = [
                'libele'=>$val->produit->libele,
                "cat"=>$val->produit->marque->categorie->libele." ". $val->produit->marque->libele,
                "enter"=>$singleProdApro,
                "trans"=>$singleProdTrans,
                "vente"=> $singleProdVente,
                "rest"=>$val->quantite
            ];
            
            array_push($prodArrayResume,$singleProdResume);
        }
        //tirer le tableau selon ordre decroissant de categorie
        usort($prodArrayResume, function ($a, $b) {
            return strcmp($b['cat'], $a['cat']);
        });
        // dd($prodArrayResume);
        
        return view('rapport.journalier', compact ( "approJour", "transJour", "venteJour","prodArrayResume"));
    }
    public function mensuel($depot){
        Carbon::setLocale('fr');
        $mois = Carbon::now()->format('m');
        $year = Carbon::now()->format('Y');
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

                //resume stock chaque produit
        $prodArrayResume = [];
        $allProdDepot = ProduitDepot::where('depot_id', $depot->id)->with('produit','produit.marque', 'produit.marque.categorie')->get();
        foreach($allProdDepot as $ke=>$val){
            $singleProdApro = Approvisionnement::where('depot_id', $depot->id)
                            ->where('produit_id',$val->produit_id)
                            ->whereMonth('created_at',$mois)
                            ->whereYear('created_at',$year)
                            ->sum('quantite');

            $singleProdVente =DB::table('ventes')
                            ->join('vente_produits', 'ventes.id', '=', 'vente_produits.vente_id')
                            ->whereYear('ventes.created_at',$year)
                            ->whereMonth('ventes.created_at',$mois)
                            ->where('produit_id',$val->produit_id)
                            ->where('depot_id', $depot->id)
                            ->sum('vente_produits.quantite');
            $singleProdTrans =DB::table('transferts')
                            ->join('produit_transferts', 'transferts.id', '=', 'produit_transferts.transfert_id')
                            ->whereYear('transferts.created_at',$year)
                            ->whereMonth('transferts.created_at',$mois)
                            ->where('produit_id',$val->produit_id)
                            ->where('depot_id', $depot->id)
                            ->sum('produit_transferts.quantite');

            $singleProdResume = [
                'libele'=>$val->produit->libele,
                "cat"=>$val->produit->marque->categorie->libele." ". $val->produit->marque->libele,
                "enter"=>$singleProdApro,
                "trans"=>$singleProdTrans,
                "vente"=> $singleProdVente,
                "rest"=>$val->quantite
            ];
            
            array_push($prodArrayResume,$singleProdResume);
        }
        //tirer le tableau selon ordre decroissant de categorie
        usort($prodArrayResume, function ($a, $b) {
            return strcmp($b['cat'], $a['cat']);
        });
        // dd($prodArrayResume);
        
        $mois = Carbon::now()->isoFormat('MMMM YYYY');;
                        
        return view('rapport.mensuel', compact ( "approMois", "transMois", "venteMois","mois","prodArrayResume"));
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
        //resume stock chaque produit
        $prodArrayResume = [];
        $allProdDepot = ProduitDepot::where('depot_id', $depot->id)->with('produit','produit.marque', 'produit.marque.categorie')->get();
        foreach($allProdDepot as $ke=>$val){
            $singleProdApro = Approvisionnement::where('depot_id', $depot->id)
                            ->where('produit_id',$val->produit_id)
                            ->whereYear('created_at',$year)
                            ->sum('quantite');

            $singleProdVente =DB::table('ventes')
                            ->join('vente_produits', 'ventes.id', '=', 'vente_produits.vente_id')
                            ->whereYear('ventes.created_at',$year)
                            ->where('produit_id',$val->produit_id)
                            ->where('depot_id', $depot->id)
                            ->sum('vente_produits.quantite');
            $singleProdTrans =DB::table('transferts')
                            ->join('produit_transferts', 'transferts.id', '=', 'produit_transferts.transfert_id')
                            ->whereYear('transferts.created_at',$year)
                            ->where('produit_id',$val->produit_id)
                            ->where('depot_id', $depot->id)
                            ->sum('produit_transferts.quantite');

            $singleProdResume = [
                'libele'=>$val->produit->libele,
                "cat"=>$val->produit->marque->categorie->libele." ". $val->produit->marque->libele,
                "enter"=>$singleProdApro,
                "trans"=>$singleProdTrans,
                "vente"=> $singleProdVente,
                "rest"=>$val->quantite
            ];
            
            array_push($prodArrayResume,$singleProdResume);
        }
        //tirer le tableau selon ordre decroissant de categorie
        usort($prodArrayResume, function ($a, $b) {
            return strcmp($b['cat'], $a['cat']);
        });
        // dd($prodArrayResume);
        return view('rapport.annuel', compact ( "approAn", "transAn", "venteAn","year","prodArrayResume"));
    }


    public function seemore($action){
        dd($action);
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
    public function facture($vente, $action){
       
        $id= $vente/56745264509;
        $findVenteDetail = Vente::where('id',$id)->first();
        if($findVenteDetail->user == null){
            $objet = new \stdClass();
            $objet->name = "Vendeur Suspendu";
            $objet->prenom = "";
            $objet->postnom = "";

            $findVenteDetail->user = $objet;
        }
        $findVenteDetail->devise = $findVenteDetail->devise ? $findVenteDetail->devise->libele : "inc";
        $findVenteDetail->taux = ($findVenteDetail->updateTaux != null) ? $findVenteDetail->updateTaux : 1;
        $data =[
            'findVenteDetail'=>$findVenteDetail, 
        ];
        // dd($findVenteDetail->taux);
        $code = str_replace('/', '-', $findVenteDetail->code);
        $facture = ($findVenteDetail->user != null)
            ? "facture ".$findVenteDetail->user->name." ".$findVenteDetail->created_at." ".$code .".pdf"
            : "facture "." ".$findVenteDetail->created_at." ".$code .".pdf";
      
        // $customPaper = array(0, 0, 227, 600); // (gauche, haut, droite, bas)
        // $pdf = Pdf::loadView('vente.fact', $data);
        // $pdf->setPaper($customPaper);
        // return $pdf->download($facture);

         $html = View::make('vente.fact', $data)->render();

        // Étape 1 – Mesurer la hauteur
        $GLOBALS['bodyHeight'] = 0;
        $dompdf1 = new Dompdf();
        $dompdf1->setPaper([0, 0, 226.77, 1000]); // 80mm largeur, hauteur temporaire
        $dompdf1->loadHtml($html);
        $dompdf1->setCallbacks([
            'collect_height' => [
                'event' => 'end_frame',
                'f' => function ($frame) {
                    $node = $frame->get_node();
                    if (strtolower($node->nodeName) === 'body') {
                        $padding_box = $frame->get_padding_box();
                        $GLOBALS['bodyHeight'] = $padding_box['h'];
                    }
                }
            ]
        ]);
    
        $dompdf1->render();

        $hauteurReelle = $GLOBALS['bodyHeight']; // marges de sécurité

        // Étape 2 – Rendu final avec hauteur exacte
        $dompdf2 = new Dompdf();
        $dompdf2->setPaper([0, 0, 226.77, $hauteurReelle]);
        $dompdf2->loadHtml($html);
        $dompdf2->render();
        if($action === 'print') {
            return $dompdf2->stream($facture, ['Attachment' => false]);
        }elseif($action === 'download') {
            return $dompdf2->stream($facture, ['Attachment' => true]);
        }else{
            back()->with('echec','Erreur inattendue');
        }
    }

    
}
