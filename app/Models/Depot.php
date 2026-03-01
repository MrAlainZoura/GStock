<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Depot extends Model
{
    use SoftDeletes;
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
        'remboursement_delay',
        'lon',
        'lat',
        'type'
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
    public function reservation(){
        return $this->hasMany(Reservation::class);
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
   public function depotSouscriptions()
    {
        return $this->hasMany(DepotSouscription::class);
    }

    public function hasActiveSouscription()
    {
        return $this->depotSouscriptions()
            ->whereHas('souscription', function ($query) {
                $query->where('expired', '>', Carbon::now());
            })
            ->exists();
    }
    public function hasNewAdmin()
    {
        $user = $this->user;
        return $user && $user->created_at->between(now()->subDays(10), now());
    }


    public function hasFullSouscription()
    {
        return $this->depotSouscriptions()
            ->whereHas('souscription', function ($query) {
                $query->where('fulltime', true);
            })
            ->exists();
    }

    public function abonnementCurrent()
    {
        return $this->hasActiveSouscription() || $this->hasFullSouscription() || $this->hasNewAdmin();
    }
    
    public function getActiveSouscription()
    {
        return $this->depotSouscriptions()
            ->whereHas('souscription', function ($query) {
                $query->where('expired', '>', Carbon::now());
            })
            ->first();
    }
    protected static function booted()
    {
        static::deleting(function ($depot) {
            // $depot->devise()->delete();
            // $depot->depotUser()->delete();
            // $depot->transfert()->delete();
            $depot->vente()->delete();
            // $depot->produitDepot()->delete();
            // $depot->approvisionnement()->delete();
        });


        static::restoring(function ($depot) {
            $depot->vente()->onlyTrashed()->restore();
        });

        static::forceDeleting(function ($depot) {
            $depot->vente()->withTrashed()->forceDelete();
        });

    }
}
