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

    public function bookTicket()
    {

        $user = \request()->user;
        $ids[] = \request()->seats;

        $seats = Seat::whereIn('id',$ids)->get();

        $trips= [];

        foreach ($seats as $i => $seat)
        {
            if($seat->status == 'booked'){
                return response()->json(['error'=>'the Seat '.$seat->id .' is already booked'],404);
            }
            $trips[$i] = $seat->CurrentTrip();
            $user->seats()->attach($seat->id,['status' => 'valid']);
            $seat->status = 'booked';
            $seat->update();
        }

        return responseFormat(['trip_data'=>$trips,'seats'=>$seats]);
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
