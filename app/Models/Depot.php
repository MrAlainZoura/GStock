<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Depot extends Model
{
    protected $fillable = ['user_id','libele'];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
