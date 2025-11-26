<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepotSouscription extends Model
{
    protected $fillable = [
        "depot_id",
        "souscription_id"
    ];

    public function souscription(){
        return $this->belongsTo(Souscription::class);
    }
    public function depot(){
        return $this->belongsTo(Depot::class);
    }
}
