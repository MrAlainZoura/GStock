<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Depot;
use App\Models\Presence;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\PDF;
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
            'user_id'=>'required|long',
            'depot_id'=>'required|long',
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
        
        $position = $this->getPerimetre((float)$service->lon, (float)$service->lat);
       //verif ip deja existe
       if($position['ok']){
            $verif_ip = Presence::where('ip', $position['ip'])
                ->whereDate('created_at', $today)
                ->exists();
            if($verif_ip ){
                return back()->with('success', "Quelqu'un a déjà signé la présence aujourd'hui avec cet appareil");
            }
       }
        $insert = [
            'user_id'=>$user->id,
            'depot_id'=>$service->id,
            'confirm'=>$position['confirmation'],
            'ip'=>$position['ip'],
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
    public function show($date)
    {
        $presence = Presence::whereYear('h_arrive',$date)->latest()->get();

        $tab= [];
        for($i=1; $i <= 12; $i++){
            $presence = Presence::whereYear('h_arrive',$date)->whereMonth('h_arrive',$i)->get();
            if(count($presence) > 0){
                switch ($i) {
                    case 1:
                        $mois = 'Janvier';
                        break;
                    
                    case 2:
                        $mois="Février";
                        break;
                    
                    case 3:
                        $mois="Mars";
                        break;
                    
                    case 4:
                        $mois="Avril";
                        break;
                    
                    case 5:
                        $mois="Mai";
                        break;
                    
                    case 6:
                        $mois="Juin";
                        break;
                    
                    case 7:
                        $mois="Juillet";
                        break;
                    
                    case 8:
                        $mois="Aout";
                        break;
                    
                    case 9:
                        $mois="Septembre";
                        break;
                    
                    case 10:
                        $mois="Octobre";
                        break;
                    
                    case 11:
                        $mois="Novembre";
                        break;
                    
                    case 12:
                        $mois="Décembre";
                        break;
                    
                    default:
                        # code...
                        break;
                }
                $presence->an=$date;
                $presence->mois=$i;
                $presence->jour=false;
                $tab[$mois] = $presence;
            }
        } 
        // dd($tab);
        $groupe=$tab;
        return view('presences.index', compact('groupe'));
    }

    public function showPresence($an, $mois){
        $presence = Presence::whereYear('h_arrive',$an)->whereMonth('h_arrive',$mois)->get();
        // dd($presence);
        $groupe=[];
        foreach($presence as $key=>$val){
            $element = Carbon::parse($val->h_arrive)->format('Y-m-d');
            // echo $element."<br>";
            if($key==0){

                $groupecle = Presence::where('created_at','like',"%$element%")->get();
                $groupecle->an=null;
                $groupecle->mois=null;
                $groupecle->jour=true;
                $groupe [$element]= $groupecle;
            }
            if($key > 0){
                
                $cle= $key-1;
                $element2 = Carbon::parse($presence[$cle]->h_arrive)->format('Y-m-d');

                if($element != $element2){
                    $groupecle2 = Presence::where('created_at','like',"%$element2%")->get();
                    $groupecle2->an=null;
                    $groupecle2->mois=null;
                    $groupecle2->jour=true;
                    $groupe [$element2]= $groupecle2;

                }
            }
            $dernier = count($presence)-1;
            if($key == $dernier){
                $groupecle = Presence::where('created_at','like',"%$element2%")->get();
                $groupecle->an=null;
                $groupecle->mois=null;
                $groupecle->jour=true;
                $groupe[$element]= $groupecle;
            }
        }

        return view('presences.index', compact('groupe'));

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

    public function updateSortier(int $id){
        $getTimeNow = Carbon::now();
        $now = $getTimeNow->format('Y-m-d H:i:s');

        $presence = Presence::find($id);        
        if(!$presence){
            return back()->with('echec',"Priere de signer d'abord la présence d'arrivée avant la sortie");
        }
       
        if($presence->created_at != $presence->update_at){
            return back()->with('echec',"Revenez le prochain jour de travail pour signer la présence, vous avez déjà signé la sortie pour aujourd'hui");
        }
       
        if($presence->update(['update_at'=>$now])){
            return back()->with('success',"Vous êtes sorti à {$now} {$presence->user->nom}");
        }

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
            if($presence->update(['confirm' => true])){
                return back()->with('success',"La présence de {$presence->user->name} a été confirmée avec succès");
            }
        }
        return back()->with('echec','Une erreur s est produite reessayer plus tard');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Presence $presence)
    {
        //
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

     private function calculDistance ( ?float $xCenter, ?float $yCenter, ?float $xUser, ?float $yUser){
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
     public function getPerimetre(float $long,float $lat, $rayon = 10){

        $coord_bureau = ["long"=>$long, "lat"=>$lat];
        $data = [
                'ok' => false,
                'long'=>null,
                'lat'=>null,
                'distance'=>null,
                'ip'=>null,
                'city'=>null,
                'confirmation'=>null
            ];

        try {
            // IP publique
            $ipResponse = Http::timeout(10)->get('https://api.ipify.org');
            $ip = $ipResponse->body();

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
            $distance = $this->calculDistance($coord_bureau['lat'],$coord_bureau['long'],$localisation['lat'],$localisation['lon']);
        
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
