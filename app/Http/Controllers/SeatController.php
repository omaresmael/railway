<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Seat;
use App\Models\User;
use Facade\Ignition\QueryRecorder\Query;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        $seats = Seat::whereIn('id',$ids)->with('car')->get();


        $trips= [];

        foreach ($seats as $i => $seat)
        {



            if($seat->status == 'booked')
            {
                return response()->json(['error'=>'the Seat '.$seat->id .' is already booked'],404);
            }

            $trips[$i] = $seat->CurrentTrip();
            if($trips[$i])
            {
                if($trips[$i]['price'] <= $user->wallet) {
                    $user->seats()->attach($seat->id, ['status' => 'valid']);
                    $seat->status = 'booked';
                    $seat->update();

                    $user->wallet -= $trips[$i]['price'];
                    $user->update();
                    return responseFormat(['trip_data' => $trips, 'seats' => $seats]);
                }

                return response()->json(['error' =>'You don\'t have enough money in your wallet'],404);

            }
            return response()->json(['error' => 'the trip is expired']);


        }



    }

    public function getTicket()
    {
        $user = \request()->user;

        return responseFormat($user->tickets());
    }
    //change the status of the ticket to expired
    public function deleteTicket(Seat $seat){
        $trip = $seat->currentTrip();
        $user = \request()->user;
        $user->seats()->detach($seat->id);
        $user->seats()->attach($seat->id,['status' => 'expired']);

        $trip->seats()->detach($seat->id);
        $trip->seats()->attach($seat->id,['status' => 'expired']);

        $seat->status = 'available';
        $seat->update();

        return response()->json(['success','Ticket Deleted Successfully'],200);

    }
}
