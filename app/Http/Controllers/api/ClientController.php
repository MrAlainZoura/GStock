<?php

namespace App\Http\Controllers\api;

use Exception;
use App\Models\Vente;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClientController extends Controller
{
    public function triPAssant(){
        try {
        $clientPrincipal = Client::whereRaw('LOWER(name) = ?', ['passant'])
            ->orderBy('id')
            ->first();

        $autresClients = Client::whereRaw('LOWER(name) = ?', ['passant'])
            ->where('id', '!=', $clientPrincipal->id)
            ->pluck('id'); // On récupère juste les IDs

        $updateVent = Vente::whereIn('client_id', $autresClients)
                    ->update(['client_id' => $clientPrincipal->id]);
        $deleteCleint = Client::whereIn('id', $autresClients)->delete();
        return response()->json(["Premier"=>$clientPrincipal, "Autres"=>$autresClients, "Update"=>$updateVent, "StatutDelete"=>$deleteCleint, "AllDelete"=>$autresClients->count()]);
        } catch (Exception $e) {
                // error_log("Exception capturée : " . $e->getMessage());
                // http_response_code(500);
                // echo json_encode(["error" => "Erreur interne"]);
             return response()->json(["erreur"=>$e->getMessage(), "status"=>false]);
        }
    }
}
