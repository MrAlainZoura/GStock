<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
     protected $fillable = [
        "vente_id",
        "tranche",
        "avance",
        "solde",
        "net",
        "completed"
    ] ;

    public function vente(){
        return $this->belongsTo(Vente::class);
    }  
}
