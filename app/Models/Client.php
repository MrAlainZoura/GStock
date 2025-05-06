<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable= [
        'name',
        'prenom',
        'tel',
        'adresse',
        "genre"
    ] ;

    public function vente(){
        return $this->hasMany(Vente::class) ;
    }
}
