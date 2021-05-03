<?php

namespace App\Http\Controllers;

use App\Http\Requests\TripRequest;
use App\Models\Train;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TripController extends Controller
{

    public function __construct()
    {

        return $this->middleware('token');

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        $trips = Trip::with('stations','baseStation','destinationStation','seats','levels')->get();
        $response = responseFormat($trips);
        return  $response;

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TripRequest $request)
    {

        $prices = [];
        $user = \request()->user;
        array_push($prices,$request->priceA,$request->priceB,$request->priceC) ;

        if($user->isAdmin()) {
            $canNotCreateTrip = 0;
            $trip = Trip::create($request->all());
            $cars = $trip->train->cars;
            $seats = [];
            $levels = [];
            foreach ($cars as $car)
            {
                $levelId = $car->level->id;
                if($car->seats()->first()->currentTrip())
                {
                    $canNotCreateTrip = 1;
                    break;
                }
                array_push($levels, $levelId);
                $id = $car->seats->pluck('id');
                array_push($seats, $id->all());
            }
            if($canNotCreateTrip)
            {
                $trip->delete();
                return response()->json(['error'=>'you can not add trip to this train for now']);
            }

            if ($request->has('stations')) {
                $trip->stations()->attach($request->has('stations'));
            }
            $seats = array_merge(...$seats);

            $trip->levels()->attach([$levels[0]=>['price'=>$prices[0]],$levels[1]=>['price'=>$prices[1]],$levels[2]=>['price'=>$prices[2]]]);

            $trip->seats()->attach($seats,['status'=>'valid']);

            return response()->json(['success'=>'Trip Added Successfully'],200);
        }
        else {
            return  response()->json(['error' => 'you\'re not authorized'],403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Trip  $trip
     * @return \Illuminate\Http\Response
     */
    public function show(Trip $trip)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Trip  $trip
     * @return \Illuminate\Http\Response
     */
    public function edit(Trip $trip)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Trip  $trip
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Trip $trip)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Trip  $trip
     * @return \Illuminate\Http\Response
     */
    public function destroy(Trip $trip)
    {
        $trip->delete();

        return response()->json(['success','Trip Deleted Successfully'],200);
    }
}
