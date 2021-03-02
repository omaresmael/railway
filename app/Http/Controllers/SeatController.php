<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Seat;
use App\Models\User;
use Facade\Ignition\QueryRecorder\Query;
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
         return responseFormat($user->tickets());
    }
    //change the status of the ticket to expired
    public function deleteTicket(Seat $seat){

        $user = \request()->user;
        $user->seats()->detach($seat->id);
        $user->seats()->attach($seat->id,['status' => 'expired']);

        $seat->status = 'available';
        $seat->update();

        return response()->json(['success','Ticket Deleted Successfully'],200);

    }
}
