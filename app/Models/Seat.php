<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;

    protected $fillable = ['status','car_id'];
    //protected $with = ['car'];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

//    public function users()
//    {
//        return $this->belongsToMany(User::class);
//    }

    public function users()
    {
    return $this->morphedByMany(User::class, 'seatable');
    }

    public function trips()
    {
        return $this->morphedByMany(Trip::class, 'seatable');
    }

    public function train()
    {
        return $this->car->train();
    }

    public function currentTrip()
    {
        $trip = $this->train->trips()->where('status','current')->first();

        $price =$this->car->level->price;
        return ['trip'=>$trip,'price'=>$price];

    }




}
