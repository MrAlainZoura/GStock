<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    protected $fillable = [
        'marque_id',
        'depot_id',
        'libele',
        'description',
        'prix',
        'quatite',
        'etat',
        'image'
    ];

    public function marque(){
        return $this->belongsTo(Marque::class);
    }
    public function depot(){
        return $this->belongsTo(Depot::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }



}
