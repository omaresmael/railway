<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Train extends Model
{
    use HasFactory;

    public function cars()
    {
        return $this->hasMany(Car::class);
    }

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

}
