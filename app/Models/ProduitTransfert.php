<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProduitTransfert extends Model
{
    protected $fillable = [
        "description",
        "transfert_id",
        "produit_id",
        "quantite"
    ];

    public function produit(){
        return $this->belongsTo(Produit::class);
    }
    public function transfert(){
        return $this->belongsTo(Transfert::class);
    }
}
