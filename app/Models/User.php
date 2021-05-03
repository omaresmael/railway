<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
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
        'phone_number',
        'wallet'
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

       $tickets = [];
        $userSeats = $this->seats()->where('seatables.status','valid')->get();

        if($this->isAdmin())
        {
            $seatIds = DB::table('seatables')->join('users', 'seatables.seatable_id', '=', 'users.id')
                ->where('seatables.seatable_type', '=', 'App\Models\User')
                ->where('seatables.status','valid')->distinct()->pluck('seat_id');
            $userSeats = Seat::whereIn('id', $seatIds)->get();

        }
        foreach ($userSeats as $i => $seat)
        {
            $user = $seat->users()->where('seatables.status','valid')->first();

            $trip_data = $seat->currentTrip();
            if(!$trip_data)
            {
                continue;
            }

            else {
                $tickets[$i] = ['user_data'=>$user,'trip_data'=>$trip_data,'ticket_time'=>$user->pivot->created_at,'seat_id'=>$user->pivot->seat_id];
            }

        }



        return $tickets;
    }

}
