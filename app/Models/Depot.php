<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Depot extends Model
{
    protected $fillable = [
        'libele',
        'user_id',
        'logo',
        'email',
        'contact1',
        'contact',
        'cpostal',
        'pays',
        'province',
        'ville',
        'avenue',
        'idNational',
        'numImpot',
        'autres',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function approvisionnement(){
        return $this->hasMany(Approvisionnement::class);
    }
    public function produitDepot(){
        return $this->hasMany(ProduitDepot::class);
    }
    public function vente(){
        return $this->hasMany(Vente::class);
    }
    public function transfert(){
        return $this->hasMany(Transfert::class);
    }
    public function depotUser(){
        return $this->hasMany(DepotUser::class);
    }
    public function devise(){
        return $this->hasMany(Devise::class);
    }

        protected static function booted()
    {
        static::deleting(function ($depot) {
            $depot->devise()->delete();
            $depot->depotUser()->delete();
            $depot->transfert()->delete();
            $depot->vente()->delete();
            $depot->produitDepot()->delete();
            $depot->approvisionnement()->delete();
        });

    }
}
