<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Approvisionnement extends Model
{
    protected $fillable = [
        'user_id',
        'depot_id',
        'produit_id',
        'quantite',
        'confirm',
        'receptionUser',
        'origine'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function depot(){
        return $this->belongsTo(Depot::class);
    }
    public function produit(){
        return $this->belongsTo(Produit::class);
    }

}
