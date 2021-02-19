<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Seat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SeatController extends Controller
{

    public function __construct()
    {
        return $this->middleware('token');
    }

    public function bookTicket($id)
    {

        $user = \request()->user;
        $seat = Seat::where('status','available')->findOrFail($id);

        $trip = $seat->CurrentTrip();

        $user->seats()->attach($seat->id,['status' => 'valid']);
        $trip->seats()->attach($seat->id,['status' => 'valid']);
        $seat->status = 'booked';
        $seat->update();

        return 'success';
    }

    public function getTicket()
    {
        $user = \request()->user;

         return $user->tickets();
//        $userSeat = $user->seats()->where('seatables.status','valid')->firstOrFail();
//        dd($userSeat->CurrentTrip());
//        $userTrip = $userSeat->trips();
//        dd($userTrip);
//
//        dd($user->seats()->where('seatables.status','valid')->firstOrFail());
//        foreach($user->seats as $seat){
//            dd($seat);
//        }

    }
}
