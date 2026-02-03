<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'user_id',
        'depot_id',
        'client_id',
        'code',
        'statut', 
        'devise_id',
        'updateTaux'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function depot(){
        return $this->belongsTo(Depot::class);
    }
    public function client(){
        return $this->belongsTo(Client::class);
    }
    public function devise(){
        return $this->belongsTo(Devise::class);
    }
    public function paiement(){
        return $this->hasMany(ReservationPaiement::class);
    }
}
