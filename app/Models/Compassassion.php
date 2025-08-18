<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compassassion extends Model
{
    protected $fillable=[
        'produit_id',
        'vente_id',
        'quantite',
        'prixU',
        'prixT',
        'reduction' 
    ];

    public function vente(){
        return $this->belongsTo(Vente::class);
    }
    public function produit(){
        return $this->belongsTo(Produit::class);
    }
}
