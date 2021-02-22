<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;

    public function cars()
    {
        return $this->hasMany(Car::class);
    }

    public function trips()
    {
        return $this->belongsToMany(Trip::class,'levels_trips')->withPivot('price');
    }
}
