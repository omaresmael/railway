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
        return $this->belongsTo(Car::class)->with('level');
    }

//    public function users()
//    {
//        return $this->belongsToMany(User::class);
//    }

    public function users()
    {
    return $this->morphedByMany(User::class, 'seatable')->withTimestamps();
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
        $trip = $this->trips()->where('seatables.status','valid')->first();

        if(!$trip) return null;

        $levelId = $this->car->level->id;
        $class = $this->car->level->class;
       $price =$trip->levels->find($levelId)->pivot->price;



        return ['trip'=>$trip,'class'=>$class,'price'=>$price];

    }






}
