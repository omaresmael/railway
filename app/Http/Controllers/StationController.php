<?php

namespace App\Http\Controllers;

use App\Models\Station;
use Illuminate\Http\Request;

class StationController extends Controller
{
    public function __construct()
    {
        return $this->middleware('token');
    }

    public function index(){
        $user = \request()->user;

        if($user->isAdmin()){
            $stations = Station::query()
            ->select('id','name')->get();
            return $stations;
        }

        else {
            return  response()->json(['error' => 'you\'re not authorized'],403);
        }


    }
}
