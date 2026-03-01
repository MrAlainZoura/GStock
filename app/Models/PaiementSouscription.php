<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaiementSouscription extends Model
{
    protected $fillable = [
        "souscription_id",
        "tranche",
        "avance",
        "solde",
        "net",
        "completed",
        "moyen",
        "reference"
    ];

    public function souscription(){
        return $this->belongsTo(Souscription::class);
    }
}
