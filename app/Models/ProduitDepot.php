<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProduitDepot extends Model
{
    protected $fillable = ['depot_id',
    'produit_id',
    'quantite'];

    public function produit(){
        return $this->belongsTo(Produit::class);
    }
    public function depot(){
        return $this->belongsTo(Depot::class);
    }
}

