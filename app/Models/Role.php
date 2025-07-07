<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['libele'];
    public function user_role(){
        return $this->hasMany(UserRole::class);
    }
}
