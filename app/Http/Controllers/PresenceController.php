<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Depot;
use App\Models\Presence;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\PDF;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class PresenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $lancement = 2020;
        $now = Carbon::now()->year;
        $tab = [];
        for($i = $lancement; $i <= $now; $i++){
            $presence = Presence::whereYear('h_arrive',$i)->latest()->get();
            if(count($presence) > 0){
                $presence->an=null;
                $presence->mois=null;
                $presence->jour=false;
                $tab[$i] = $presence;
            }  
        }

        return $tab;
        // return view('presences.index', compact('tab'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($action)
    {
        return view('presences.create',compact('action'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validattion = Validator::make($request->all(), [
            'user_id'=>'required|int',
            'depot_id'=>'required|int',
        ]);
        if($validattion->fails()){
            $message = $validattion->errors();
            return back()->with('echec',$message);
        }
        $time = Carbon::now();
        $today = $time->format('Y-m-d');
        $service_h = Carbon::createFromFormat('H:i','07:00');
        $arrive_serv = Carbon::createFromFormat('H:i',$time->format('H:i'));
        // return 'on teste heure';
        if($arrive_serv < $service_h){
            return back()->with('echec',"Le travail commence au moins à 7h");
        }
      
        $user = User::find($request->user_id);
        $service  = Depot::find($request->depot_id);
        if(!$user || !$service){
           return back()->with('echec',"Erreur renseignemenet invalide");   
        }
        //verifier si presence existe deja pour ce jour
        $verif_prese = Presence::where('user_id', $request->user_id)
            ->whereDate('created_at', $today)
            // ->latest()
            ->first();

        if ($verif_prese && Carbon::parse($verif_prese->h_arrive)->isSameDay($today)) {
            return back()->with('success', "Signer la sortie et revenez le prochain jour de travail");
        }
        
        $position = self::getPerimetre((float)$service->lon, (float)$service->lat,10,$request->ip());
       //verif ip deja existe
        $verif_ip = Presence::where('ip', $request->ip())
            ->whereDate('created_at', $today)
            ->exists();
        if($verif_ip ){
            return back()->with('success', "Quelqu'un a déjà signé la présence aujourd'hui avec cet appareil");
        }

        $insert = [
            'user_id'=>$user->id,
            'depot_id'=>$service->id,
            'confirm'=>$position['confirmation'],
            'ip'=>$request->ip(),
            'distance'=>$position['distance'],
            'lon'=>$position['long'],
            'lat'=>$position['lat'],
            'city'=>$position['city']
        ];
        
        $presence = Presence::create($insert);
        if($presence){
            return back()->with('success',"Vous avez signé votre présence à $presence->created_at");
        }
        return back()->with('echec',"Une erreur s'est produite, veuillez réessayer");
    }

    /**
     * Display the specified resource.
     */
    public function show($depot_id)
    {
        $depot  = Depot::find($depot_id);
        if(!$depot){
           return back()->with('echec',"Erreur renseignemenet invalide");   
        }
        $today = Carbon::now();

        $presence = Presence::where('depot_id', $depot_id)
            ->whereDate('created_at', $today->format('Y-m-d'))
            ->get();
        return view('presence.journalier', compact('presence','depot'));
    }

    public function presenceMensuel($depot_id){
        $depot  = Depot::find($depot_id);
        if(!$depot){
           return back()->with('echec',"Erreur renseignemenet invalide");   
        }
        
        $now = Carbon::now();
        $an = $now->year; 
        $mois = $now->month;
        Carbon::setLocale('fr');

        $presence = Presence::with(['user','depot'])
            ->whereYear('created_at', $an)
            ->whereMonth('created_at', $mois)
            ->where('depot_id', $depot->id)
            ->get()
            ->groupBy(function($item) {
                return $item->created_at->translatedFormat('l j F Y');
            });
            // return $presence;
        return view('presence.mensuel', compact('presence', 'depot'));
    }
    public function showPresenceJour($date){
        $presence = Presence::where('created_at','like',"%$date%")->get();
        return view('presences.show',compact('presence'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Presence $presence)
    {
        //
    }

    public function updateSortie(int $id){
        $getTimeNow = Carbon::now();
        $now = $getTimeNow->format('Y-m-d H:i:s');

        $presence = Presence::find($id);        
        if(!$presence){
            return back()->with('echec',"Priere de signer d'abord la présence d'arrivée avant la sortie");
        }
       
        if($presence->created_at->equalTo($presence->updated_at)){
            $presence->updated_at = $now;
            $presence->save();
            return back()->with('success',"Vous êtes sorti à {$now} {$presence->user->name}");
        }
        return back()->with('echec',"Revenez le prochain jour de travail pour signer la présence, vous avez déjà signé la sortie pour aujourd'hui à {$presence->updated_at}");
     }
    /**
     * Update the specified resource in storage.
     */
    public function update(int $id)
    {
        $getTimeNow = Carbon::now();
        $now = $getTimeNow->format('Y-m-d H:i:s');//maintenant temps de la requette        
        
        $presence = Presence::find($id);

        if($presence){
            $limite = Carbon::createFromFormat('Y-m-d H:i:s',$presence->created_at,'UTC');
            $jourHier = Carbon::createFromFormat('Y-m-d',$limite->format('Y-m-d'),'UTC')->setTime(00,00,00);
            $today = $jourHier->addDay()->addHours(12);

            if($now >= $today){
                return back()->with('echec','Tentative de corruption, dépasser 12h00 la présence ne peut être modifiée');
            }
            if(in_array(Auth::user()->user_role->role->libele,['Administrateur','Super admin']) && $presence->update(['confirm' => true])){
                return back()->with('success',"La présence de {$presence->user->name} a été confirmée avec succès");
            }
        }
        return back()->with('echec','Une erreur s est produite reessayer plus tard');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $presence = Presence::find($id);
        if(in_array(Auth::user()->user_role->role->libele,['Administrateur','Super admin']) && $presence){
            $presence->delete();
            return back()->with('success', 'Suppression reussie avec succes');
        }
        return back()->with('echec', 'Erreur demande introuvable');
    }
    
    public function globalPresence(){
 
        $lancement = 2020;
        $now = Carbon::now()->year;
        $tab = [];
        
        for($i = $lancement; $i <= $now; $i++){
            $presence = Presence::whereYear('h_arrive',$i)->latest()->get();
            if(count($presence) > 0){
                $tab_mois=[];
                for($mois=1; $mois <= 12; $mois++){
                    $presence_mois = Presence::whereYear('h_arrive',$i)->whereMonth('h_arrive',$mois)->get();

                    if(count($presence_mois) > 0){
                        $tab_jour=[];
                        foreach($presence_mois as $key=>$val){
                            $element = Carbon::parse($val->h_arrive)->format('Y-m-d');
                            if($key==0){
                                $tab_jourcle = Presence::where('created_at','like',"%$element%")->get();
                                $tab_jour [$element]= $tab_jourcle;
                            }
                            if($key > 0){
                                
                                $cle= $key-1;
                                $element2 = Carbon::parse($presence_mois[$cle]->h_arrive)->format('Y-m-d');
                
                                if($element != $element2){
                                    $tab_jourcle2 = Presence::where('created_at','like',"%$element2%")->get();
                                    $tab_jour [$element2]= $tab_jourcle2;
                                }
                            }
                            $dernier = count($presence_mois)-1;
                            if($key == $dernier){
                                $tab_jourcle = Presence::where('created_at','like',"%$element2%")->get();

                                $tab_jour[$element]= $tab_jourcle;
                            }
                        }
                        $tab_mois[$mois]=$tab_jour;
                       
                    }
                } 
                $tab[$i]=$tab_mois;
            }  
        }
       return view('presences.global', compact('tab'));        
    }

    public function getIntervalDate($date)
    {
        $date_mois = Carbon::parse($date)->format('Y-m-d') ;
        $date_ref = Carbon::createFromFormat('Y-m-d H:i:s', $date);
        $date_ref0 = Carbon::createFromFormat('Y-m-d H:i:s', $date);

        $debut_mois = $date_ref0->startOfMonth();
        $fin_mois = $date_ref0->endOfMonth();

        $debut_semaine = $date_ref0->startOfWeek(Carbon::MONDAY);
        $fin_semaine = $date_ref0->endOfWeek(Carbon::SUNDAY);

        return [
            'debut_semaine'=>Carbon::parse($debut_semaine)->format('Y-m-d') ,
            'fin_semaine'=>Carbon::parse($fin_semaine)->format('Y-m-d'),
            'debut_mois'=>Carbon::parse($debut_mois)->format('Y-m-d'),
            'fin_mois'=>Carbon::parse($fin_mois)->format('Y-m-d')
        ];
    }

    static private function calculDistance ( ?float $xCenter, ?float $yCenter, ?float $xUser, ?float $yUser){
        if ($xCenter === null || $yCenter === null || $xUser === null || $yUser === null) {
            return null;
        }

        try {
            $diff = $yCenter - $yUser;
            $distance = sin(deg2rad($xCenter))*sin(deg2rad($xUser)) 
                        + cos(deg2rad($xCenter))*cos(deg2rad($xUser))*cos(deg2rad($diff));
            
            $distance = acos($distance);
            $distance = rad2deg($distance);
            
            $km = $distance * 60 * 1.1515;
            $mettres = $km *1609.39;

            return $mettres;
        }catch(Exception $e){
            return null;
        }
    }
    /**
     * Calcule un périmètre autour d'une coordonnée.
     *
     * @param float $long Longitude
     * @param float $lat Latitude
     * @param float $rayon Rayon en m (par défaut 10)
     */
     static public function getPerimetre(float $long,float $lat, $rayon = 10, string $requestIp = ''){

        $coord_bureau = ["long"=>$long, "lat"=>$lat];
        $data = [
                'ok' => false,
                'long'=>null,
                'lat'=>null,
                'distance'=>null,
                'ip'=>null,
                'city'=>null,
                'confirmation'=>false
            ];

        try {
            // IP publique
            $ipResponse = Http::timeout(10)->get('https://api.ipify.org');
            // $ip = $ipResponse->body();
            $ip = ($requestIp == '')? $ipResponse->body() : $requestIp;

            // Géolocalisation
            $url = "http://ip-api.com/json/{$ip}";
            $positionResponse = Http::timeout(10)->get($url);

            if ($positionResponse->failed()) {
                $data["error"] ="API ip-api.com inaccessible";
                return $data;
            }

            // Décoder la réponse JSON
            $localisation = $positionResponse->json();

           //Rayon de 10m par defaut autour du bureau
            $distance = self::calculDistance($coord_bureau['lat'],$coord_bureau['long'],$localisation['lat'],$localisation['lon']);
        
               return [
                    'ok'=>true,
                    'long'=>$localisation['lon'],
                    'lat'=>$localisation['lat'],
                    'distance'=>$distance,
                    'ip'=>$localisation['query'],
                    'city'=>$localisation['city'],
                    'confirmation'=>($distance <= $rayon)?true:false
                ];
            
        } catch (Exception $e) {
            $data["error"] = $e->getMessage();
            return $data;
        }
    }
}
