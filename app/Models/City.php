<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = ['name', 'province_id'];

    public function province(){
        return $this->belongsTo(Province::class);
    }

    public function Users() {
        return $this->hasMany(User::class, 'user_id');
    }
}
