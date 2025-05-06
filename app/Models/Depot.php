<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Depot extends Model
{
    protected $fillable = ['libele','user_id'];

    public function user(){
        return $this->hasMany(User::class);
    }

    public function approvisionnement(){
        return $this->hasMany(Approvisionnement::class);
    }
    public function produitDepot(){
        return $this->hasMany(ProduitDepot::class);
    }
    public function vente(){
        return $this->hasMany(Vente::class);
    }
    public function depotUser(){
        return $this->hasMany(DepotUser::class);
    }


}
