<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = ['depart_time','arrival_time','base_id','destination_id','train_id'];
    protected $with = ['baseStation:id,name','destinationStation:id,name'];

    protected static function boot()
    {
        parent::boot();

        Trip::creating(function($model) {
            $baseStation = Station::find(request()->base_id);
            $destinationStation = Station::find(request()->destination_id);
            $baseLongitude = $baseStation->longitude;
            $baseLatitude = $baseStation->latitude;

            $destinationLongitude = $destinationStation->longitude;
            $destinationLatitude = $destinationStation->latitude;
            $model->distance = distance($baseLatitude,$baseLongitude,$destinationLatitude,$destinationLongitude);
        });
    }

    public function stations()
    {
        return $this->belongsToMany(Station::class);
    }

    public function baseStation()
    {
        return $this->belongsTo(Station::class,'base_id');
    }

    public function destinationStation()
    {
        return $this->belongsTo(Station::class,'destination_id');
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }
    public function seats()
    {
        return $this->morphToMany(Seat::class, 'seatable')->withTimestamps()->with('car');
    }

    public function train()
    {
        return $this->belongsTo(Train::class);
    }
    public function levels()
    {
        return $this->belongsToMany(Level::class,'levels_trips')->withPivot('price');
    }

}
