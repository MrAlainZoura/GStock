<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

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

    public function depot(){
        return $this->belongsTo(Depot::class);
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
