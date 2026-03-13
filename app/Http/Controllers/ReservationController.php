<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Depot;
use App\Models\Client;
use App\Models\Devise;
use App\Models\Categorie;
use App\Models\Reservation;
use App\Models\ProduitDepot;
use Illuminate\Http\Request;
use function PHPSTORM_META\type;
use App\Models\ReservationProduit;

use App\Models\ReservationPaiement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($depot_id, $tranche=null)
    {
        $depot = Depot::find($depot_id/13);
        // $depot = $reservation->first()->depot;
        $tranche = ($tranche)? $tranche : 0;
        if($depot){
            $month = Carbon::now()->subMonths($tranche)->format('Y-m-d');
            $reservation = $depot->reservation
                    ->where('created_at', '>=',$month )->reverse();
                // ->where('created_at', '<=', Carbon::today()->endOfDay());
            $tranche = ($tranche==1) ? null : $tranche;
            // dd($reservation, $depot);
            return view('reservation.index', compact('depot', 'reservation', 'tranche')) ;
        }
        return back()->with('echec',"Une erreur s'est produite, reessayer plus tard");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($depot_id)
    {
        // dd('redirect vue create');
        $depot= Depot::where('id', $depot_id)->first();
        $cat = Categorie::all();
        $client =Client::all();
        $produit = ProduitDepot::where("depot_id",$depot->id)->with("produit.marque","produit.marque.categorie")->get();
        return view("reservation.create", compact("depot","cat","client","produit"));
    
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $dossier = 'pieceIdentite';
        
        if (!Storage::disk('direct_public')->exists($dossier)) {
            Storage::disk('direct_public')->makeDirectory($dossier);
        }
        $now = Carbon::now();
        // [$partie, $partie2]  = str_split( $now->getMicrosecond(),3);
        $micro = $now->format('u'); // retourne 6 chiffres
        [$partie, $partie2] = str_split($micro, 3);

        $code = $this->initialNameAdmin()."_$partie"."_$partie2";
       
        // dd($request->all(), $request->reservations);

        $validateDate = Validator::make($request->all(),
        [
            'nom_client',
            'lieu_de_vente'=>'string|required',
            'contact_client'=>'max:255',
            'reservations'=>'array|required'
        ]);
        if($validateDate->fails()){
            return back()->with('echec',$validateDate->errors());
        }

        $netPayer = 0;
        foreach($request->reservations as $id => $reservation){
            $netPayer += $reservation['montant'];
            $booked = self::checkInterval($reservation['startAt'],$reservation['endAt'], $id);
           if($booked['disponible']){
                return back()->with('echec', $booked['data'] ." sera disponible apres ".$booked['heure']);
           } 
        }

        $fichier = $request->file('image');
        $type = ($request->file('image')!=null)? $fichier->getClientOriginalExtension():'';
                           
        $dataClient = [
            'name'=>$request->nom_client,
            'prenom'=>$request->prenom,
            'tel'=>$request->contact_client,
            'adresse'=>$request->adresse,
            "genre"=>$request->genre,
            "peice_identite"=>$request->piece,
            "numero_piece"=>$request->numeroPiece,
        ];
        $filterDataClient = array_filter($dataClient, function($val){return !is_null($val);});
        $client_id ="";

        $tel = $request->contact_client;
        $findClient = null;
        (strlen($tel) <= 10)? $lonTel="+243".substr($tel, 1):$lonTel=$tel;
 
        if($tel != null){
            $findClient = Client::where('tel', $tel)
                ->orWhere('tel', $lonTel)
                ->Where('tel', "!=", null)
                ->first();
             // si image piece est nulle et image request != de null on ajoute l'image
             if ($findClient && $request->hasFile('image') && $findClient->image_piece != null) {
                 $newImageName = $findClient->name.'_'.$code.$type;
                 $findClient->update(['numero_piece'=>$newImageName]);
                 $fichierStore = $request->file('image')->storeAs(
                     $dossier,
                     $newImageName,
                     'direct_public'
                 );
             }

            }elseif($request->nom_client == null || strtolower(trim($request->nom_client)) == "passant" ){
                $findClient = Client::WhereRaw('LOWER(name) = ?', ['passant'])
                   ->first();
            }
            if($findClient != null){
                $client_id =$findClient->id;
            }else{
                ($request->hasFile('image'))? $filterDataClient["image_piece"]=$request->nom_client.'_'.$code.".".$type:"";
                // dd($filterDataClient);

                $createClient = Client::create($filterDataClient);
                $client_id = $createClient->id;

                if ($request->hasFile('image')) {
                    $fichierStore = $request->file('image')->storeAs(
                        $dossier,
                        $createClient->image_piece,
                        'direct_public'
                    );
                }
                
            }
        
            preg_match('/^(\d+)-(.*)$/', $request->monnaie, $m)
                ? [$devise, $libele] = [$m[1], $m[2]]
                : [$devise, $libele] = [null, null];
            $getDeviseId = Devise::where('id', $devise)->where('libele',$libele)->first();
            $devise_id = ($getDeviseId) ? $getDeviseId->id : null; 

            $dataReservation = [
                'client_id'=>$client_id,
                'code'=>$code,
                'statut'=>"Encours", 
                'devise_id'=>$devise_id,
                'user_id'=>Auth::user()->id,
                'depot_id'=>$request->depot_id/98123,
                'updateTaux'=>$request->updateDevise
            ];
            $reservationCreate = Reservation::create($dataReservation);
            // dd($dataReservation);
            foreach($request->reservations as $id => $reservation){
                $dataReservationPro =[
                    "produit_id"=>$id,
                    "reservation_id"=>$reservationCreate->id,
                    "duree"=>self::getDuree2( $reservation['startAt'], $reservation['endAt']),
                    "debut"=>Carbon::parse( $reservation['startAt'])->format('Y-m-d H:i:s'),
                    "fin"=>Carbon::parse( $reservation['endAt'])->format('Y-m-d H:i:s'),
                    "montant"=>$reservation['montant'],
                    // "reduction"=>""
                ];
                ReservationProduit::create($dataReservationPro);
            }
           
        $checkTranche = filter_var($request->tranche, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        $dataPaiement  = [
            'reservation_id'=> $reservationCreate->id,
            "tranche"=>1,
            "avance"=>($checkTranche==true) ? (float)$request->trancheP : $netPayer,
            "solde"=>($checkTranche==true) ? (float)$netPayer - (float)$request->trancheP:0,
            "net"=>$netPayer,
            "completed"=>($checkTranche == true ) ? false : true
        ];
        $createPaiement = ReservationPaiement::create($dataPaiement);
        return to_route("reservation.show", $reservationCreate->id*56);
        // dd($reservationCreate, $reservationCreate->reservationProduit, $reservationCreate->paiement);
    }

    /**
     * Display the specified resource.
     */
     public function show($reservation)
    {
        $id= $reservation/56;
        $detailVente = Reservation::where('id',$id)->first();
        if($detailVente == null){
            return back()->with('echec','Renseignements fournient sont invalides');
        }
        session(['depot' => $detailVente->libele]);
        session(['depot_id' => $detailVente->id]);
        $depotId = $detailVente->depot->id;
        $userRole = Auth::user()->user_role->role->libele;
        $user_id = Auth::user()->id;
        $userDepotAffectation = Auth::user()->depotUser;

        $detailVente->devise = $detailVente->devise ? $detailVente->devise->libele : "inc";
        $detailVente->taux = ($detailVente->updateTaux != null) ? $detailVente->updateTaux : 1;

        $existAffectation = $userDepotAffectation->filter(function ($u) use ($depotId) {
                        return isset($u->depot_id) && $u->depot_id === $depotId;
                    })->first();

         $roleAutorises = ['Administrateur', 'Super admin'];
        if (!in_array($userRole, $roleAutorises)) {
            if($existAffectation === null){
                // dd($affectation,"pas d'acces dans ce depot");
                return to_route('dashboard')->with('echec',"Vous n'avez pas accès à ce depot!"); 
            }
            // dd($detailVente->reservationProduit[0]->produit->image, $detailVente->taux, $detailVente->devise);
            return view('reservation.show', compact('detailVente')) ;
        }
        if($userRole === $roleAutorises[0]){
            $adminDepotCreateur = Auth::user()->depot->filter(function ($u) use ($user_id) {
                return isset($u->user_id) && $u->user_id === $user_id;
            })->first();
            // dd(  'administrateur', $adminDepotCreateur); 
            if( $adminDepotCreateur === null ){
                return to_route('dashboard')->with('echec',"Vous n'avez pas accès à ce depot!"); 
            }
            return view('reservation.show', compact('detailVente')) ;
        }
        if($userRole === $roleAutorises[1]){
            return view('reservation.show', compact('detailVente')) ;
        }   
    }

     public function creance($depotId){
        // dd($depot, $id, "creance");
        // if(session('depot') === null){
        //     return to_route('dashboard');
        // }
        $id = $depotId/13;

        $depot = Depot::where('id', $id)->first();
        $depotId = $depot->id;
        $depotCreance = Reservation::with(['client', 'reservationProduit']) 
                        ->whereHas('paiement', function ($query) {
                            $query->where('completed', false);
                        })
                        ->where('depot_id', $depotId)
                        ->get();
        $tabSyntese=[];
        foreach($depotCreance as $k=>$v){
            if(!array_key_exists($v->vente_id, $tabSyntese)){
                foreach($v->reservationProduit as $c=>$val){
                    $prod[] = $val->produit->marque->libele.' '.$val->produit->libele;
                }
                foreach($v->paiement as $cl=>$vl){
                    $tranche[]=$vl->avance. " - ".$vl->solde;
                }
                $dernierVersement = count($v->paiement)-1;
                $completed = $v->paiement[$dernierVersement]->completed;
                $tabSyntese[$v->id] = [
                    'vendeur'=>$v->user->name." ".$v->user->postnom." ".$v->user->prenom,
                    'client'=>['nom'=>$v->client->name.' '.$v->client->prenom.' '.$v->client->postnom,'tel'=> $v->client->tel,'date'=> $v->created_at] ,
                    'prod'=>$prod, 
                    'tranche'=>$tranche, 
                    'net'=>$v->paiement[0]->net,
                    'completed'=>$completed,
                    'devise'=>$v->devise->libele
                ];
                $prod=[];
                $tranche=[];
            }
    
        }
        $tabSyntese= array_reverse($tabSyntese, true);
        // dd($tabSyntese, 'ok');
        return view('reservation.creance', compact('tabSyntese',"depot"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reservation $resevation)
    {
        return back()->with( 'success',"Bientot disponible");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reservation $resevation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($resevation)
    {
        if(!in_array(Auth::user()->user_role->role->libele,  ['Administrateur', 'Super admin'])){
            return back()->with("echec","Vous ne disposez pas de droit necessaire pour effectuer cette action");
        }
        $delete = Reservation::find($resevation);
        if($delete){
            $depotId = $delete->depot_id;
            $delete->delete();
            return to_route('reservation.list',$depotId*13)->with( 'success',"Réservation supprimée avec succès");
        }
        return back()->with( 'echec',"Impossible de trouver cette réservation, réessayer plus tard!");
    }

     public function restore($reservationId){

        if(!in_array(Auth::user()->user_role->role->libele,  ['Administrateur', 'Super admin'])){
            return back()->with("echec","Vous ne disposez pas de droit necessaire pour effectuer cette action");
        }
        $id = $reservationId/12;
        $reservation = Reservation::with(['reservationProduit', 'paiement'])->onlyTrashed()->find($id);
        if($reservation){
            $depot = $reservation->depot;
            $reservation->restore();
            return to_route('reservation.crash', [ "depot_id"=>$depot->id*12])->with("success","Réservation restaurée avec succès !");
        }
        return back()->with('success', "Erreur, renseignement fourni incorrect !");
     }

     public function reservationTrashed($depot){
        // dd($depot/12);
        if(!in_array(Auth::user()->user_role->role->libele,  ['Administrateur', 'Super admin'])){
            return back()->with("echec","Vous ne disposez pas de droit necessaire pour effectuer cette action");
        }
        $depot_id = $depot/12;
        $depot= Depot::where('id', $depot_id)->first();
        if($depot){
            // dd($depot->vente, $depot->devise);
           $vente= Reservation::where('depot_id', $depot->id)
           ->onlyTrashed()
           ->orderBy('deleted_at', 'desc')
           ->with([
                'reservationProduit',
                'paiement'
            ])->get();
            return view('reservation.trashed', compact('depot', 'vente')) ;
        }else{
            return back()->with("echec","Erreur demande introuvable");
        }
     }
     public function forcedelete($reservationId){
        if(!in_array(Auth::user()->user_role->role->libele,  ['Administrateur', 'Super admin'])){
            return back()->with("echec","Vous ne disposez pas de droit necessaire pour effectuer cette action");
        }
        $id = $reservationId/12;
        $reservation = Reservation::onlyTrashed()->find($id);
        $depot= $reservation->depot;
        // dd($vente);
        if($reservation){
            $reservation->forceDelete();
            return to_route('reservation.crash', ["depot_id"=>$depot->id*12])->with("success","Réservation supprimée définitivement avec succès !");
            // return back()->with('success', "Vente supprimée définitivement avec succès !");
        }
        return back()->with('echec', "Suppression échouée, erreur inattendue s'est produite!");
     }

     public function initialNameAdmin(String $name=""){
        $name = ($name==null) ? "abcdefghijklmnopqrstuvwxyz" : $name;
        $first = $name[ rand(0, strlen($name)-1)];
        $second = $name[ rand(0, strlen($name)-1)];
        return strtoupper( $first.$second);
    }

    public static function checkInterval($newDebut, $newFin, $id ){
        $reservation = ReservationProduit::where(function ($query) use ($newDebut, $newFin) {
                        $query->whereBetween('debut', [$newDebut, $newFin])
                              ->orWhereBetween('fin', [$newDebut, $newFin])
                              ->orWhere(function ($q) use ($newDebut, $newFin) {
                                  $q->where('debut', '<=', $newDebut)
                                    ->where('fin', '>=', $newFin);
                              });
                    })
                    ->whereHas( "reservation")
                    ->where( 'produit_id', $id)
                    ->orderBy('fin', 'asc') // la plus proche qui se termine
                    ->first();

        if ($reservation) {
            $disponibleApres = $reservation->fin;
            return [
                'heure' =>Carbon::parse( $disponibleApres)->format('Y-m-d H:i:s'),
                'disponible' => true,
                'data' => "{$reservation->produit->libele} ({$reservation->produit->marque->libele})"
            ];
        }
        return [
                'heure' => null,
                'disponible' => false
        ];
    }

    public static function getDuree($start, $end) {
        $startAt = Carbon::parse($start);
        $endAt   = Carbon::parse($end);

        // Différence brute en minutes
        $diffMinutes = $startAt->diffInMinutes($endAt);
        // Selon le cas
        if ($diffMinutes < 60) {
            $result = $diffMinutes . ' minutes';
        } elseif ($diffMinutes < 24 * 60) {
            $result = $startAt->diffInHours($endAt) . ' heures';
        } elseif ($diffMinutes < 30 * 24 * 60) {
            $result = $startAt->diffInDays($endAt) . ' jours';
        } elseif ($diffMinutes < 365 * 24 * 60) {
            $result = $startAt->diffInMonths($endAt) . ' mois';
        } else {
            $result = $startAt->diffInYears($endAt) . ' années';
        }

        return $result;

    }

    public static function getDuree2($start, $end) {
            $startAt = Carbon::parse($start);
            $endAt   = Carbon::parse($end);

            $diff = $startAt->diff($endAt); // retourne un DateInterval

            $parts = [];

            if ($diff->y > 0) {
                $parts[] = $diff->y . ' ' . ($diff->y > 1 ? 'années' : 'année');
            }
            if ($diff->m > 0) {
                $parts[] = $diff->m . ' ' . ($diff->m > 1 ? 'mois' : 'mois');
            }
            if ($diff->d > 0) {
                $parts[] = $diff->d . ' ' . ($diff->d > 1 ? 'jours' : 'jour');
            }
            if ($diff->h > 0) {
                $parts[] = $diff->h . ' ' . ($diff->h > 1 ? 'heures' : 'heure');
            }
            if ($diff->i > 0) {
                $parts[] = $diff->i . ' ' . ($diff->i > 1 ? 'minutes' : 'minute');
            }

            return implode(' et ', $parts);
    }
}
