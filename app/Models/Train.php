<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Train extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function cars()
    {
        return $this->hasMany(Car::class);
    }

    public function levels()
    {
        return $this->belongsToMany(Level::class,'cars');
    }

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }
    public function baseStations()
    {
        return $this->belongsToMany(Station::class,'trips','train_id','base_id');
    }
    public function destinationStations()
    {
        return $this->belongsToMany(Station::class,'trips','train_id','destination_id');
    }

}
