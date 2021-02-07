<?php

namespace App\Http\Controllers;

use App\Models\Seat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SeatController extends Controller
{

//    public function __construct()
//    {
//        return $this->middleware('token');
//    }

    public function bookTicket($id){
        $user = User::first();

        dd($user->seaats);

        $user->seaats()->create([
            'status' => 'current',
            'seat_id'=> $id,
            'seatable_id' => $user->id,
            'seatable_type' => get_class($user)
        ]);
        return 'done';
    }

    public function getTicket($id){
        $user = User::find(4);
        foreach($user->seats as $seat){
            dd($seat);
        }

    }
}
