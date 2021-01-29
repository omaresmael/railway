<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = ['depart_time','arrival_time','base_id','destination_id','trip_id'];

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
}
