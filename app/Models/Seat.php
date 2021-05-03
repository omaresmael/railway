<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

        $depart_time = $trip->depart_time;
        $depart_time = str_replace( array(":"), '', $depart_time);
        $now = str_replace(array(":"),'',Carbon::now('Africa/Cairo')->toTimeString());
        if($depart_time < $now)
        {
             DB::table('seatables')
                ->where('seatables.seatable_type', '=', 'App\Models\Trip')
                ->where('seatables.seatable_id',$trip->id)->update(['status' =>'expired']);

            DB::table('seatables')
                ->where('seatables.seatable_type', '=', 'App\Models\User')
                ->where('seatables.seatable_id',$this->id)->update(['status' =>'expired']);

            $this->status = 'available';
            $this->save();

            $trip->status = 'expired';
            $trip->save();
            return null;
        }



        $levelId = $this->car->level->id;
        $class = $this->car->level->class;
       $price =$trip->levels->find($levelId)->pivot->price;



        return ['trip'=>$trip,'class'=>$class,'price'=>$price];

    }






}
