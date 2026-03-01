<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject

{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'genre',
        'naissance',
        'fonction',
        'niveauEtude',
        'option',
        'adresse',
        'tel',
        'depot_id',
        'postnom',
        'prenom',
        'image'
    ];
    
    // jwt guard
    protected $guard_name = 'api';

    public function depot(){
        return $this->hasMany(Depot::class);
    }
    public function approvisionnement(){
        return $this->hasMany(Approvisionnement::class);
    }
    public function vente(){
        return $this->hasMany(Vente::class);
    }
    public function depotUser(){
        return $this->hasMany(DepotUser::class);
    }

    public function souscription(){
        return $this->hasMany(Souscription::class);
    }
    public function maxDepot(){
        $getNombre = $this->souscription()?->latest()->first()?->abonnement->max;
        return ($getNombre)
                ? $getNombre
                : 3;
    }
    public function presence(){
        return $this->hasMany(Presence::class);

    }
    public function user_role()
    {
        return $this->hasOne(UserRole::class);
    }
/**
     * mehtod pour jwt
     *
     * @var list<string>
     */
     public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
