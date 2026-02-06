<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservationProduit extends Model
{
      protected $fillable=[
        "produit_id",
        "reservation_id",
        "duree",
        "debut",
        "fin",
        "montant",
        "reduction"
    ];

    public function produit(){
        return $this->belongsTo(Produit::class);
    }

    public function reservation(){
        return $this->belongsTo(Reservation::class);
    }  
}
