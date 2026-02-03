<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservationPaiement extends Model
{
     protected $fillable = [
        "reservation_id",
        "tranche",
        "avance",
        "solde",
        "net",
        "completed"
    ] ;

    public function reservation(){
        return $this->belongsTo(Reservation::class);
    }  
}
