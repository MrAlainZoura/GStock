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

        if ($clientPrincipal) {
            $autresClients = Client::whereRaw('LOWER(name) = ?', ['passant'])
                ->where('id', '!=', $clientPrincipal->id)
                ->pluck('id');
            $updateVente = false;
            $deleteClient = false;
            if ($autresClients->isNotEmpty()) {
                // Mise à jour des ventes
                $updateVente = Vente::withTrashed()
                    ->whereIn('client_id', $autresClients)
                    ->update(['client_id' => $clientPrincipal->id]);
                // Suppression des clients
                 $deleteClient = Client::whereIn('id', $autresClients)->delete();
            }
        }

        return response()->json(["Premier"=>$clientPrincipal, "Autres"=>$autresClients, "Update"=>$updateVente, "StatutDelete"=>$deleteClient, "AllDelete"=>$autresClients->count()]);
        } catch (Exception $e) {
             return response()->json(["erreur"=>$e->getMessage(), "status"=>false]);
        }
    }
}
