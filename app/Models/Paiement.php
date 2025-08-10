<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Paiement extends Model
{
     use SoftDeletes;
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
