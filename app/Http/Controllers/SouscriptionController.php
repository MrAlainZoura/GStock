<?php

namespace App\Http\Controllers;

use App\Enum\AbonnementType;
use DateTime;
use Carbon\Carbon;
use App\Models\Abonnement;
use App\Models\Souscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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

        $exists = Souscription::where('expired', ">=", $expire)
                ->where('user_id', $request->user_id)
                ->where('abonnement_id', $typeAb->id)
                ->exists();

        if ($exists) {
            return "Abonnement du meme type encours";
            // return back()->with('echec', "Vous avez déjà une souscription active pour cet abonnement.");
        }

        if(Souscription::create($data)){
           return to_route("abonnement.list", (int)$data['user_id']*13);
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
    public function destroy(Souscription $souscription)
    {
        //
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
}
