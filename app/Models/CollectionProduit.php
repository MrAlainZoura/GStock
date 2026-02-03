<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CollectionProduit extends Model
{
    protected $fillable = [
        "produit_id",
        "name",
        "quantite",
        "description"
    ];

    public function produit(){
        return $this->belongsTo(Produit::class);
    }
}
