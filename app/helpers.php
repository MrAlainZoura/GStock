<?php

use App\Models\Depot;

if (! function_exists('depot')) {
    function depot($id = null)
    {
        if ($id) {
            return Depot::find($id);
        }
        return null;
        // Exemple si tu veux récupérer le dépôt lié à l’utilisateur connecté
        // return auth()->user()?->depot;
    }
}