<?php

namespace App\Http\Controllers;

use App\Jobs\SendDailyReport;
use DateTime;
use Carbon\Carbon;
use Dompdf\Dompdf;
use App\Models\Depot;
use App\Models\Vente;
use App\Models\UserRole;
use App\Models\Transfert;
use Carbon\CarbonInterval;
use App\Models\ProduitDepot;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Approvisionnement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;

class RapportController extends Controller
{
    public function journalier($depot, $id){
        // dd($depot, $id, "journalier");

        $today = Carbon::now()->format('Y-m-d');
        $depot = Depot::where('libele',$depot)->where('id', $id)->first();
        if(!$depot){
            return back()->with("echec", "Erreur, impossible de trouver le depot");
        }
        $compassassion = Vente::whereHas('compassassion', function ($query) use ($today) {
                $query->whereDate('created_at', $today );
            })
            ->with(['paiement','venteProduit','compassassion'])
            ->where("depot_id", $depot->id)
            ->get();
        // dd($compassassion[0]->venteProduit, $compassassion[0]->compassassion, $compassassion[0]->paiement, $dernierPaiement = $compassassion[0]->paiement->sortByDesc('created_at')->first(), $compassassion);

        $approJour = Approvisionnement::orderBy('user_id')->where('depot_id', $depot->id)
                    ->whereDate('created_at',$today)
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
        // usort($prodArrayResume, function ($a, $b) {
        //     return strcmp($b['cat'], $a['cat']);
        // });
        usort($prodArrayResume, function ($a, $b) {
            // Tri par 'rest' (numérique croissant)
            $restCompare = $a['rest'] <=> $b['rest'];
            // Si 'rest' est égal, on trie par 'cat' (alphabétique croissant)
            if ($restCompare === 0) {
                return strcmp($a['cat'], $b['cat']);
            }
            return $restCompare;
        });
        // dd($prodArrayResume);
        
        return view('rapport.journalier', compact ( "approJour", "transJour", "venteJour","prodArrayResume", "depot", "compassassion"));
    }
    public function mensuel($depot, $id){
        // dd($depot, $id, "mensuel");
        if(!$depot){
            return back()->with("echec", "Erreur, impossible de trouver le depot");
        }
        Carbon::setLocale('fr');
        $mois = Carbon::now()->format('m');
        $year = Carbon::now()->format('Y');
        $depot = Depot::where('libele',$depot)->where('id', $id)->first();
        if(!$depot){
            return back()->with("echec", "Erreur, impossible de trouver le depot");
        }
        $approMois = Approvisionnement::orderBy('user_id')->where('depot_id', $depot->id)
                    ->whereMonth('created_at',$mois)
                    ->get();
        $transMois = Transfert::orderBy('user_id')->where('depot_id', $depot->id)
                    ->whereMonth('created_at',$mois)
                    ->get();
        $venteMois = Vente::orderBy('user_id')->where('depot_id', $depot->id)
                        ->whereMonth('created_at',$mois)
                        ->get();
         $compassassion = Vente::whereHas('compassassion', function ($query) use ($mois) {
                $query->whereMonth('created_at', $mois );
            })
            ->with(['paiement','venteProduit','compassassion'])
            ->where("depot_id", $depot->id)
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
        // usort($prodArrayResume, function ($a, $b) {
        //     return strcmp($b['cat'], $a['cat']);
        // });
        usort($prodArrayResume, function ($a, $b) {
            // Tri par 'rest' (numérique croissant)
            $restCompare = $a['rest'] <=> $b['rest'];
            // Si 'rest' est égal, on trie par 'cat' (alphabétique croissant)
            if ($restCompare === 0) {
                return strcmp($a['cat'], $b['cat']);
            }
            return $restCompare;
        });
        // dd($prodArrayResume);
        
        $mois = Carbon::now()->isoFormat('MMMM YYYY');;
                        
        return view('rapport.mensuel', compact ( "approMois", "transMois", "venteMois","mois","prodArrayResume", "depot", "compassassion"));
    }
    public function annuel($depot, $id){
        // dd($depot, $id, "annuel");
        $year = Carbon::now()->format('Y');
        $depot = Depot::where('libele',$depot)->where('id', $id)->first();
        if(!$depot){
            return back()->with("echec", "Erreur, impossible de trouver le depot");
        }
        $approAn = Approvisionnement::orderBy('user_id')->where('depot_id', $depot->id)
                    ->whereYear('created_at',$year)
                    ->get();
        $transAn = Transfert::orderBy('user_id')->where('depot_id', $depot->id)
                    ->whereYear('created_at',$year)
                    ->get();
        $venteAn = Vente::orderBy('user_id')->where('depot_id', $depot->id)
                        ->whereYear('created_at',$year)
                        ->get();
        $compassassion = Vente::whereHas('compassassion', function ($query) use ($year) {
                        $query->whereYear('created_at', $year );
                    })
                    ->with(['paiement','venteProduit','compassassion'])
                    ->where("depot_id", $depot->id)
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
        // usort($prodArrayResume, function ($a, $b) {
        //     return strcmp($b['cat'], $a['cat']);
        // });
        usort($prodArrayResume, function ($a, $b) {
            // Tri par 'rest' (numérique croissant)
            $restCompare = $a['rest'] <=> $b['rest'];
            // Si 'rest' est égal, on trie par 'cat' (alphabétique croissant)
            if ($restCompare === 0) {
                return strcmp($a['cat'], $b['cat']);
            }
            return $restCompare;
        });

        return view('rapport.annuel', compact ( "approAn", "transAn", "venteAn","year","prodArrayResume", "depot", "compassassion"));
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
        if(!$findVenteDetail){
            return to_route('dashboard')->with('echec', "Facture introuvable. Elle a peut-être été supprimée !");
        }
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
            ? "facture ".$findVenteDetail->user->name." ".$findVenteDetail->created_at." ".$code ."-".$findVenteDetail->client->name." ".$findVenteDetail->client->tel.".pdf"
            : "facture "." ".$findVenteDetail->created_at." ".$code."-".$findVenteDetail->client->name." ".$findVenteDetail->client->tel.".pdf";
      
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

          // Étape 2 – Rendu final avec hauteur exacte
        $dompdf2 = new Dompdf();
        $dompdf2->setPaper([0, 0, 226.77, $hauteurReelle]);
        $dompdf2->loadHtml($html);
        $dompdf2->render();
        
        // if($action === 'print') {
        //     return $dompdf2->stream($facture, ['Attachment' => false]);
        // }
        
        if ($action === 'print') {
            return response($dompdf2->output(), 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="' . $facture . '"');
        }elseif($action === 'download') {
            return $dompdf2->stream($facture, ['Attachment' => true]);
        }else{
           return back()->with('echec','Erreur inattendue');
        }
    }

  /**
     * Génère un PDF à partir d’un rapport par ID et une periode (aujourd'hui, mois ou annee).
     */
   
    public static function genererPDF($id, $periode = 'today')
    {
        $depot = Depot::find($id);
        $limite = Carbon::today()->setHour(17)->setMinute(30);
        // $periode = "mois";
        $dateFilter = function ($table = null) use ($periode) {
            return function ($query) use ($periode, $table) {
                $column = $table ? "{$table}.created_at" : "created_at";
                switch ($periode) {
                    case 'mois':
                        $query->whereMonth($column, Carbon::now()->month)
                            ->whereYear($column, Carbon::now()->year);
                        break;
                    case 'annee':
                        $query->whereYear($column, Carbon::now()->year);
                        break;
                    default:
                        $query->whereDate($column, Carbon::today());
                        break;
                }
            };
        };

        // Requêtes principales
        $approJour = Approvisionnement::where('depot_id', $depot->id)
            ->where($dateFilter())
            ->orderBy('user_id')
            ->get();

        $transJour = Transfert::where('depot_id', $depot->id)
            ->where($dateFilter())
            ->orderBy('user_id')
            ->get();
        if($periode == "today"){
            
            $venteJourPremierTour = Vente::where('depot_id', $depot->id)
                ->where($dateFilter())
                ->where('created_at', '<', $limite)
                ->orderBy('user_id')
                ->get();
            $venteJourDeuxiemeTour = Vente::where('depot_id', $depot->id)
                ->where($dateFilter())
                ->where('created_at', '>=', $limite)
                ->orderBy('user_id')
                ->get();
            $venteJour=[
                'avant'=>$venteJourPremierTour,
                'apres'=>$venteJourDeuxiemeTour,
            ];
    
            $compassassionPremierTour = Vente::whereHas('compassassion', function ($query) use ($limite, $dateFilter) {
                    $query->where('created_at', '<', $limite)
                            ->where($dateFilter());
                })
                ->with(['paiement', 'venteProduit', 'compassassion'])
                ->where('depot_id', $depot->id)
                ->get();
            $compassassionDeuxiemeTour = Vente::whereHas('compassassion', function ($query) use ($limite, $dateFilter) {
                    $query->where('created_at', '>=', $limite)
                            ->where($dateFilter());
                })
                ->with(['paiement', 'venteProduit', 'compassassion'])
                ->where('depot_id', $depot->id)
                ->get();
           
            $compassassion = [
                'avant'=>$compassassionPremierTour,
                'apres'=>$compassassionDeuxiemeTour
            ];
            // dd($compassassion);
            $restePaiementTranche = Vente::with(['paiement' => function ($query) use ($dateFilter) {
                $query->where($dateFilter());
            }])->whereHas('paiement', function ($query) use ($dateFilter) {
                $query->where($dateFilter());
            })->wherenot($dateFilter())
            ->where('depot_id',$depot->id)
            ->whereDoesntHave('compassassion')
            ->get();
            
            // dd($restePaiementTranche);
            // Somme des avances dans les paiements filtrés
            $avanceTotal = $restePaiementTranche->sum(function ($vente) {
                return $vente->paiement->sum(function ($paiement) use ($vente) {
                    return $paiement->avance * $vente->updateTaux;
                });
            });
        }else{
            $venteJour = Vente::where('depot_id', $depot->id)
                ->where($dateFilter())
                ->orderBy('user_id')
                ->get();
    
            $compassassion = Vente::whereHas('compassassion', $dateFilter())
                ->with(['paiement', 'venteProduit', 'compassassion'])
                ->where('depot_id',$depot->id)
                ->get();
    
            $restePaiementTranche = Vente::with(['paiement' => function ($query) use ($dateFilter) {
                $query->where($dateFilter());
            }])->whereHas('paiement', function ($query) use ($dateFilter) {
                $query->where($dateFilter());
            })->wherenot($dateFilter())
            ->where('depot_id',$depot->id)
            ->whereDoesntHave('compassassion')
            ->get();
            // dd($restePaiementTranche);
            // Somme des avances dans les paiements filtrés
            $avanceTotal = $restePaiementTranche->sum(function ($vente) {
                return $vente->paiement->sum(function ($paiement) use ($vente) {
                    return $paiement->avance * $vente->updateTaux;
                });
            });
            $venteJour = Vente::where('depot_id', $depot->id)
                ->where($dateFilter())
                ->orderBy('user_id')
                ->get();
    
            $compassassion = Vente::whereHas('compassassion', $dateFilter())
                ->with(['paiement', 'venteProduit', 'compassassion'])
                ->where('depot_id',$depot->id)
                ->get();
    
            $restePaiementTranche = Vente::with(['paiement' => function ($query) use ($dateFilter) {
                $query->where($dateFilter());
            }])->whereHas('paiement', function ($query) use ($dateFilter) {
                $query->where($dateFilter());
            })->wherenot($dateFilter())
            ->whereDoesntHave('compassassion')
            ->get();
            // dd($restePaiementTranche);
            // Somme des avances dans les paiements filtrés
            $avanceTotal = $restePaiementTranche->sum(function ($vente) {
                return $vente->paiement->sum(function ($paiement) use ($vente) {
                    return $paiement->avance * $vente->updateTaux;
                });
            });
        }
        // Statistiques vendeurs (toujours sur le mois)
        $vendeurs = Vente::selectRaw('user_id, COUNT(*) as count, depot_id')
            ->whereMonth('created_at', Carbon::now()->month)
            ->where('depot_id', $depot->id)
            ->groupBy('user_id', 'depot_id')
            ->orderByDesc('count')
            ->get();

        // Produits du dépôt
        $allProdDepot = ProduitDepot::where('depot_id', $depot->id)
            ->with('produit.marque.categorie')
            ->get();

        // Résumés par produit
        $appros = Approvisionnement::where('depot_id', $depot->id)
            ->where($dateFilter())
            ->select('produit_id', DB::raw('SUM(quantite) as total'))
            ->groupBy('produit_id')
            ->pluck('total', 'produit_id');

        $ventes = DB::table('ventes')
            ->join('vente_produits', 'ventes.id', '=', 'vente_produits.vente_id')
            ->where($dateFilter('ventes')) 
            ->where('ventes.depot_id', $depot->id)
            ->select('vente_produits.produit_id', DB::raw('SUM(vente_produits.quantite) as total'))
            ->groupBy('vente_produits.produit_id')
            ->pluck('total', 'vente_produits.produit_id');

        $transferts = DB::table('transferts')
            ->join('produit_transferts', 'transferts.id', '=', 'produit_transferts.transfert_id')
            ->where($dateFilter( 'transferts'))
            ->where('depot_id', $depot->id)
            ->select('produit_id', DB::raw('SUM(produit_transferts.quantite) as total'))
            ->groupBy('produit_id')
            ->pluck('total', 'produit_id');

        $prodArrayResume = $allProdDepot->map(function ($val) use ($appros, $ventes, $transferts) {
            return [
                'libele' => $val->produit->libele,
                'cat' => $val->produit->marque->categorie->libele . ' ' . $val->produit->marque->libele,
                'enter' => $appros[$val->produit_id] ?? 0,
                'trans' => $transferts[$val->produit_id] ?? 0,
                'vente' => $ventes[$val->produit_id] ?? 0,
                'rest' => $val->quantite,
            ];
        })->sortBy('rest')->values()->all();
        // debut new tri
        $ventesParCategorie = $allProdDepot
            ->map(function ($val) use ($ventes) {
                $produit = $val->produit;
                $categorie = $produit->marque->categorie->libele;
                $marque = $produit->marque->libele;
                $quantiteVendue = $ventes[$val->produit_id] ?? 0;

                return [
                    'categorie' => $categorie,
                    'marque' => $marque,
                    'quantite' => $quantiteVendue,
                ];
            })
            ->filter(function ($item) {
                return $item['quantite'] >= 1;
            })
            ->groupBy('categorie')
            ->map(function ($group) {
                return $group
                    ->groupBy('marque')
                    ->map(function ($items) {
                        return $items->sum('quantite');
                    })
                    ->sortDesc(); // Tri des marques par quantité vendue
        });
        // fin new tri
        switch ($periode) {
            case 'mois':
                $getDate ='Mensuel '. Carbon::now()->translatedFormat('F Y');
               break;
            case 'annee':
                $getDate ="Annuel ". Carbon::now()->format('Y');
                break;
            default:
                $getDate ="Journalier ". Carbon::today()->format('Y-m-d');
                break;
        }
        $rapport = [
            'approvisionnement' => $approJour,
            'transfert' => $transJour,
            'vente' => $venteJour,
            'resumeProduit' => $prodArrayResume,
            'vendeurs' => $vendeurs,
            'compassassion' => $compassassion,
            'periode' => $getDate,
            'avanceTotal'=>$avanceTotal,
            'depot'=>$depot,
            'venteTri'=>$ventesParCategorie,
            'showVente'=>$ventesParCategorie->count()
        ];
        // dd($rapport, $prodArrayResume, $restePaiementTranche[0]->paiement);
        return Pdf::loadView('mail.rapport', ['rapport' => $rapport]);
    }
    public static function rapport_send_mail($to, $depot, $depot_id){
        
        // $to = 'a.tshiyanze@gmail.com';
        // mbunzucalvin@gmail
        // $object = 'Rapport PDF';
        // $contenu = 'Voici le contenu du rapport.';
        Carbon::setLocale('fr');
        $today = Carbon::now()->format('Y-m-d');
        $moisEtAnnee = Carbon::now()->translatedFormat('F Y'); // "août 2025"    $finsDuMois = [];
        $annee = Carbon::now()->format('Y');
        $today = Carbon::today()->format('Y-m-d');
        $user = Auth::user();
        $name = $user ? "{$user->name} {$user->postnom} {$user->prenom}" : "System automatique";

        for ($mois = 1; $mois <= 12; $mois++) {
            // Crée une date au premier jour du mois suivant
            $date = new DateTime("$annee-" . str_pad($mois, 2, '0', STR_PAD_LEFT) . "-01");
            $date->modify('last day of this month');
            $finsDuMois[] = $date->format('Y-m-d');
        }


        try {
            
            $sendRapport = function ($periode, $label, $filename) use ($depot_id, $to, $depot, $name) {
                $pdf = self::genererPDF($depot_id, $periode);
                Mail::send([], [], function ($message) use ($pdf, $to, $label, $depot, $name, $filename) {
                    $message->to($to)
                        ->subject("Rapport $label $depot")
                        ->html("<p>Le rapport $label en pièce jointe, envoyé par $name</p>")
                        ->attachData($pdf->output(), "rapport_{$filename}_{$depot}.pdf");
                });
            };
            // Cas : fin d’année
            $lastDayOfYear = end($finsDuMois);
            if ($today === $lastDayOfYear) {
                $sendRapport('annee', "Annuel $annee", $annee);
                $sendRapport('mois', "Mensuel $moisEtAnnee", $moisEtAnnee);
                $sendRapport('today', "journalier $today", $today);

            // Cas : fin de mois
            } elseif (in_array($today, $finsDuMois)) {
                $sendRapport('mois', "Mensuel $moisEtAnnee", $moisEtAnnee);
                $sendRapport('today', "journalier $today", $today);

            // Cas : jour ordinaire
            } else {
                $sendRapport('today', "journalier $today", $today);
            }

            return response()->json([
                'message' => 'Email envoyé avec succès.',
                'status'=>true
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur envoi PDF : ' . $e->getMessage());

            return response()->json([
                'error' => 'Échec de l’envoi du mail.',
                'details' => $e->getMessage(),
                'status'=>false
            ], 500);
        }

    }
    public function sendMailrapport($depot){
        $depot_id = $depot/123;
        $getDepot = Depot::find($depot_id);
        if(!$getDepot){
            return back()->with('echec', "Renseignement fourni est incorrect");
        }
        $pdf = self::genererPDF($depot_id, 'today');
        $today = Carbon::now()->format('Y-m-d');
        // dd($getDepot, $depot_id);
        if($getDepot != null){
            // dd($getDepot->user->email);
            $to = $getDepot->user->email;
            $sendMailRapport = self::rapport_send_mail($to,$getDepot->libele,$getDepot->id);
            if($sendMailRapport->getData()->status == true){
                $rapportDwl= 'rapport_' . $today."_$getDepot->libele" . '.pdf';
                return $pdf->download($rapportDwl); 
            }
            return back()->with('echec',"Email non envoyé, adresse mail invalide << $to >>!");
            // dd($sendMailRapport->getData()->details, 'echec');
        }
        return back()->with('echec',"Email non envoyé, renseignements fournient sont erronés !");


    }

    public static function rapportDownload ($depot, $periode){
        $depot_id = $depot/12;
        $getDepot = Depot::find($depot_id);
        if(!$getDepot){
            return back()->with('echec', "Erreur, Depot introvable");
        }

        if(in_array($periode, ['today','mois', 'annee'])){
            switch ($periode) {
                    case 'mois':
                        $getDate = Carbon::now()->translatedFormat('F Y');
                       break;
                    case 'annee':
                        $getDate = Carbon::now()->format('Y');
                        break;
                    default:
                        $getDate = Carbon::today()->format('Y-m-d');
                        break;
                }
            $pdf = self::genererPDF($depot_id, $periode);
            $rapportDwl= 'rapport_'.$getDate."_$getDepot->libele" . '.pdf';
            return $pdf->download($rapportDwl);
        }
        return back()->with('echec', "Erreur, Intervalle invalide");
    }
    public function sendMailrapportJob($depot)
    {
        
        SendDailyReport::dispatchSync($depot);
        return back()->with('success', 'Rapport envoyé avec succès.');

        // SendDailyReport::dispatch($depot);
        // die('ok regarde log '.$depot);
        // return back()->with('success', 'Le rapport est en cours d’envoi.');
    }

}
