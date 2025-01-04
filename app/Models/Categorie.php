<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    protected $fillable = ['libele','image'];

    public function marque(){
        return $this->hasMany(Marque::class);
    }

}
