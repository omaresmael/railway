<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isAdmin(){
        return $this->currentAccessToken()->abilities == '["admin"]';
    }

//    public function seats()
//    {
//        return $this->belongsToMany(User::class);
//    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favoritable')->withPivot('status');
    }

    public function seats()
    {

        return $this->morphToMany(Seat::class, 'seatable')->withTimestamps();
    }

    public function tickets() {
        $user = \request()->user;
       $tickets = [];
        $userSeats = $this->seats()->where('seatables.status','valid')->get();
        foreach ($userSeats as $i => $seat)
        {
            $trip_data = $seat->currentTrip();
            $trip = $trip_data['trip'];
            $depart_time = $trip->depart_time;
            $depart_time = str_replace( array(":"), '', $depart_time);
            $now = str_replace(array(":"),'',Carbon::now('Africa/Cairo')->toTimeString());
            if($depart_time < $now)
            {
                $seat->status = 'available';
                $user->seats()->detach($seat->id);
                $user->seats()->attach($seat->id,['status' => 'expired']);
                $trip->seats()->detach($seat->id);
                $trip->seats()->attach($seat->id,['status' => 'expired']);
            }
            else {
                $tickets[$i] = ['user_data'=>$this,'trip_data'=>$trip_data,'ticket_time'=>$seat->pivot->created_at,'seat_id'=>$seat->pivot->seat_id];
            }

        }



        return $tickets;
    }

}
