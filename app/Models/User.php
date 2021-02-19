<?php

namespace App\Models;

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
        return $this->morphMany(Favorite::class, 'favoritable');
    }

    public function seats()
    {

        return $this->morphToMany(Seat::class, 'seatable');
    }

    public function tickets() {
       $tickets = [];
        $userSeats = $this->seats()->where('seatables.status','valid')->get();
        foreach ($userSeats as $i => $seat)
        {
            $tickets[$i] = [['trip_data'=>$seat->CurrentTrip()],['seat_id'=>$seat->pivot->seat_id]];


        }



        return $tickets;
    }

}
