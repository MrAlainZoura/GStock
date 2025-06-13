<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vente extends Model
{
    protected $fillable = [
        'user_id',
        'depot_id',
        'client_id',
        'code',
        'type'
    ] ;

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function depot(){
        return $this->belongsTo(Depot::class);
    }
    public function client(){
        return $this->belongsTo(Client::class);
    }

    public function venteProduit(){
        return $this->hasMany(VenteProduit::class);
    }
    public function paiement(){
        return $this->hasMany(Paiement::class);
    }
}
