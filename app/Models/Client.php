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
        "genre",
        "peice_identite",
        "numero_piece",
        "image_piece"
    ] ;

    public function vente(){
        return $this->hasMany(Vente::class) ;
    }
    public function reservation(){
        return $this->hasMany(Reservation::class) ;
    }
}
