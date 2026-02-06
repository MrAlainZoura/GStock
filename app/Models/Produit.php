<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    protected $fillable = [
        'marque_id',
        'libele',
        'description',
        'prix',
        'etat',
        'image',
        'unite'
    ];

    public function marque(){
        return $this->belongsTo(Marque::class);
    }

    public function approvisionnement(){
        return $this->hasMany(Approvisionnement::class);
    }
    public function produitDepot(){
        return $this->hasMany(ProduitDepot::class);
    }
    public function venteProduit(){
        return $this->hasMany(VenteProduit::class);
    }
    public function produitTransfert(){
        return $this->hasMany(ProduitTransfert::class);
    }
    public function produitCollection(){
        return $this->hasMany(CollectionProduit::class);
    }
}
