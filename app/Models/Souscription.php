<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Souscription extends Model
{
    protected $fillable = [
        "user_id",
        "abonnement_id",
        "duree",
        "montant",
        "remise",
        "progres",
        "bonus",
        "code",
        "used",
        "validate",
        "fulltime",
        "debut",
        "expired"
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function abonnement(){
        return $this->belongsTo(Abonnement::class);
    }
    public function depotSouscription(){
        return $this->hasMany(DepotSouscription::class);
    }
}
