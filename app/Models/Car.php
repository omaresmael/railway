<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    public function seats()
    {
        return $this->hasMany(Seat::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }
}
