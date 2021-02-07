<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;

    protected $fillable = ['status'];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }


}
