<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vente extends Model
{
     use SoftDeletes;
    protected $fillable = [
        'user_id',
        'depot_id',
        'client_id',
        'code',
        'type', 
        'devise_id',
        'updateTaux'
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
    public function devise(){
        return $this->belongsTo(Devise::class);
    }

    public function venteProduit(){
        return $this->hasMany(VenteProduit::class);
    }
    public function paiement(){
        return $this->hasMany(Paiement::class);
    }

    protected static function booted()
    {
        static::deleting(function ($vente) {
            $vente->venteProduit()->delete();
            $vente->paiement()->delete();
        });

        static::restoring(function ($vente) {
            $vente->venteProduit()->onlyTrashed()->restore();
            $vente->paiement()->onlyTrashed()->restore();
        });

        static::forceDeleting(function ($vente) {
            $vente->venteProduit()->withTrashed()->forceDelete();
            $vente->paiement()->withTrashed()->forceDelete();
        });

    }



}
