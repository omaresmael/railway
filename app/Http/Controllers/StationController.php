<?php

namespace App\Http\Controllers;

use App\Models\Station;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class StationController extends Controller
{
    public function __construct()
    {
        return $this->middleware('token');
    }

    public function index(){
        $user = \request()->user;


            $stations = Station::query()
            ->select('id','name')->get();

           return responseFormat($stations);


    }
}
