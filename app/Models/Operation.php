<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    protected $fillable = [
        'user_id',
        'depot_id',
        'produit_id',
        'quantite',
        'libele',
        'netPayer',
        'client',
        'tel',
        'destinationDepot',
        'receptionUser',
        'status'
    ];
}
