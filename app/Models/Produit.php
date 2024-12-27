<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    protected $fillable = [
        'marque_id',
        'libele',
        'description',
        'prix',
        'quatité',
        'etat',
        'image'
    ];

    public function marque(){
        return $this->belongsTo(Marque::class);
    }
}
