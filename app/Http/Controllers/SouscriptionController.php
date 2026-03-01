<?php

namespace App\Http\Controllers;

use App\Enum\AbonnementType;
use App\Models\Abonnement;
use App\Models\Depot;
use App\Models\DepotSouscription;
use App\Models\PaiementSouscription;
use App\Models\Souscription;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Exists;

class SouscriptionController extends Controller
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
         $validateDate = Validator::make($request->all(),
        [
            'abonnement_id'=>'required|exists:abonnements,id',
            'user_id'=>'required|exists:users,id',
            "duree"=>"required|int",
            "debut"=>"required",
        ]);
        if($validateDate->fails()){
            return $validateDate->errors();
            // return back()->with('echec',$validateDate->errors());
        }
        $typeAb = Abonnement::find($request->abonnement_id);
        $validTypes = array_map(fn($type) => $type->value, AbonnementType::all());
        if (!$typeAb || !in_array($typeAb->name, $validTypes)) {
            return back()->with('echec', "Abonnement non pris en charge");
        }

        [$montant, $red] = $this->calculerMontantAvecReduction($typeAb->prix,$request->duree);
        [$debut, $expire]  = $this->ajouterMois($request->debut, (int)$request->duree);
        $microSecond = new DateTime();
        $now = Carbon::now();
        // [$partie, $partie2]  = str_split( $microSecond->getMicrosecond(),3);
        $micro = $now->format('u'); // retourne 6 chiffres
        [$partie, $partie2] = str_split($micro, 3);

        $code = $this->initialNameAdmin()."-$partie-$partie2";
        $data = [
            "user_id"=>$request->user_id,
            "abonnement_id"=>$typeAb->id,
            "duree"=>$request->duree,
            "montant"=>$montant*(int)$request->duree,
            "remise"=>$red,
            "bonus"=>$request->bonus,
            "code"=>$code,
            "progres"=>$typeAb->max,
            "debut"=>$debut,
            "expired"=>$expire,
        ];

        if (Auth::user()->userCurrentSouscription()) {
            return back()->with('success', "Vous avez déjà une souscription d'abonnement encours, effectuer une nuvelle souscription après que celle-ci ait expirée");
        }
        
        $createSouscrption = Souscription::create($data);
        if($createSouscrption){
            $notif = MailController::sendMail('a.tshiyanze@gmail.com',"Confirmation paiment",["code"=>$createSouscrption, "route"=>route("souscr.validate", Crypt::encrypt($createSouscrption->id)), "routeFull"=>route("souscr.fulltime", Crypt::encrypt($createSouscrption->id))]);
            return to_route("abonnement.list", $createSouscrption->user_id*13)->with('success', "Souscription réussite, contactez l'administrateur pour effectuer le paiement");
        }
        return back()->with('echec', "Une erreur inattendue s'est produite veuillez réessayer");
    }

    /**
     * Display the specified resource.
     */
    public function show(Souscription $souscription)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Souscription $souscription)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Souscription $souscription)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        $id = $id/13;
        $souscription = Souscription::find($id);
        $user = $souscription?->user->id;
        $isAdmin = Auth::user()->id == $user;
        $isSuperAdmin = Auth::user()->user_role->role->libele == 'Super admin';
        // dd('ok', $souscription);
        $admin = ($isAdmin || $isSuperAdmin) ? true : false;
        if ($souscription && $admin){
            $souscription->delete();
            return to_route("abonnement.list", $user*13)->with('success', "Souscription supprimée avec succès");
        }
        return back()->with('echec', "La suppression a echouée, une erreur s'est produite!");
    }

    public function ajouterMois($dateInitiale, $nombreDeMois) {
        // $dateInitiale au format "jj/mm/aaaa"
        list($jour, $mois, $annee) = explode("/", $dateInitiale);
        $debut = DateTime::createFromFormat('Y-m-d', "$annee-$mois-$jour");
        $expire = DateTime::createFromFormat('Y-m-d', "$annee-$mois-$jour");
        $expire->modify("+$nombreDeMois months");
        return [$debut->format('Y-m-d'), $expire->format('Y-m-d')];
    }

    public function calculerMontantAvecReduction($montant, $dureeAbonnement, $reductionParTrimestre = 2, $reductionMax = 20) {
        if ($dureeAbonnement < 3) {
            return [round($montant), 0];
        }

        $dureeValide = $dureeAbonnement - ($dureeAbonnement % 3);
        $trimestres = floor($dureeValide / 3);
        $reductionTotale = min($trimestres * $reductionParTrimestre, $reductionMax);
        $montantFinal = $montant * (1 - $reductionTotale / 100);

        return[ round($montantFinal), $reductionTotale];
    }

    public function initialNameAdmin(String $name=""){
        $name = ($name==null) ? "abcdefghijklmnopqrstuvwxyz" : $name;
        $first = $name[ rand(0, strlen($name)-1)];
        $second = $name[ rand(0, strlen($name)-1)];
        return strtoupper( $first.$second);
    }

    /**
     * Validation paiement d'abonnement.
     */
    public function validate($id){
        $id = decrypt($id);
        $sousc = Souscription::find($id);
        $isSuperAdmin = Auth::user()->user_role->role->libele == 'Super admin';
        // dd($isSuperAdmin, $sousc, Depot::first());
        if($sousc && $isSuperAdmin && !$sousc->validate){
            $dataPaiement = [
                "souscription_id"=>$sousc->id,
                "tranche"=>1,
                "avance"=>$sousc->montant,
                "solde"=>0,
                "net"=>$sousc->montant,
                "completed"=>true,
                "moyen"=>'M-Pesa',
                "reference"=>""
            ];
            $createPaiment = PaiementSouscription::create($dataPaiement);
            $sousc->validate = true;
            $sousc->save();
            return response()->json( ["success"=>"Abonnement activé avec succès", "souscription"=>$sousc]);
        }
        return response()->json( ["echec"=>"Authorization denied"]);
    }
    public function validateFulltime($id){
        $id = decrypt($id);
        $sousc = Souscription::find($id);
        if($sousc && !$sousc->fulltime){
            $sousc->fulltime = true;
            $sousc->save();
            return response()->json( ["success"=>"Abonnement full time activé avec succès", "souscription"=>$sousc]);
        }
        return response()->json( ["echec"=>"Authorization denied"]);
    }
    /**
     * Activation d'abonnement souscrit à un depot.
     */
    public function active($depot_id, Request $request){
        $sousc = Souscription::where('code', $request->code)->first();
        $depot = Depot::find($depot_id);
        if($sousc && $depot){
            $checkAdmin = $sousc->id == $depot->user->id;
            if($checkAdmin && $sousc->validate && !$sousc->used && (int)$sousc->progres > 0){
                $checkAffectationAbonnement = DepotSouscription::where('souscription_id', $sousc->id)
                                            ->where('depot_id', $depot->id)
                                            ->exists();
                if(!$checkAffectationAbonnement){
                    $createAffectation = DepotSouscription::firstOrCreate(['depot_id'=>$depot->id, 'souscription_id'=>$sousc->id]);
                    ($sousc->progres == 1)? $sousc->used = true : null;
                    $sousc->progres -= 1;
                    $sousc->save();
                    return back()->with('success', "Abonnement activé avec succès !");
                }
                return back()->with('echec', "Ce code a déjà été utilisé pour ce depot");
            }
            return back()->with('echec', "Ce code est invalide ou a déjà atteint le nombre d'activation maximal");
        }
        return back()->with('echec', "Ce code est invalide, entrer un code valide e réessayer");
    }
}
