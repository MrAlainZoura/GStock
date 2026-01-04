<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Presence extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'depot_id',
        'confirm',
        'ip',
        'distance',
        'lon',
        'lat',
        'city'
    ] ;

     public function user(){
        return $this->belongsTo(User::class);
    }
    public function depot(){
        return $this->belongsTo(Depot::class);
    }
}
