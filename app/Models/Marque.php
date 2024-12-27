<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marque extends Model
{
    protected $fillable = ['libele','categorie_id'];

    public function categorie(){
        return $this->belongsTo(Categorie::class);
    }
    public function produit(){
        return $this->hasMany(Categorie::class);
    }

    
}
