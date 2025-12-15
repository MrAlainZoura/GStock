<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Abonnement extends Model
{
    protected $fillable = [
        "name",
        "prix",
        "max",
        "description"
    ];

    public function souscription(){
        return $this->hasMany(Souscription::class);
    }
   
}
