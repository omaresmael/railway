<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = ['depart_time','arrival_time','base_id','destination_id','train_id'];
    protected $with = ['baseStation:id,name','destinationStation:id,name'];

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
        return $this->morphToMany(Seat::class, 'seatable');
    }

    public function train()
    {
        return $this->belongsTo(Train::class);
    }

}
