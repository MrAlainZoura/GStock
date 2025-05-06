<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transfert extends Model
{
    protected $fillable = [ 
        "user_id",
        "confirm",
        "receptionUser",
        "destination",
        "code",
        "description",
        "depot_id"
        ] ;
        
        public function user(){
            return $this->belongsTo(User::class);
        }
        public function depot(){
            return $this->belongsTo(Depot::class);
        }

        public function produitTransfert(){
            return $this->hasMany(ProduitTransfert::class);
        }
   
}
