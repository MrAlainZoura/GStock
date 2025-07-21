<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Devise extends Model
{
    protected $fillable = [
        'depot_id',
        'libele',
        'taux'
    ];

    public function depot(){
        return $this->belongsTo(Depot::class);
    }
    public function vente(){
        return $this->hasMany(Vente::class);
    }
}
