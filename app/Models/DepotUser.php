<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepotUser extends Model
{
    protected $fillable = ["depot_id","user_id"];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function depot(){
        return $this->belongsTo(Depot::class);
    }
}
