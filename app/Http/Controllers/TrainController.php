<?php

namespace App\Http\Controllers;

use App\Models\Train;
use Illuminate\Http\Request;

class TrainController extends Controller
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

        $trains = Train::with('baseStations','destinationStations')->get();
        $response = responseFormat($trains);
        return  $response;
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' =>'required',
        ]);
        $user = \request()->user;
        if($user->isAdmin()) {
            $train = Train::create($validated);

            return response()->json(['success' => $train], 200);
        }
        return  response()->json(['error' => 'you\'re not authorized'],403);
    }

    public function addCarsToTrain(Train $train, Request $request)
    {
        for($i = 0; $i<$request->A; $i++)
        {
            $train->levels()->attach(1);
        }
        for($i = 0; $i<$request->B; $i++)
        {
            $train->levels()->attach(2);
        }
        for($i = 0; $i<$request->C; $i++)
        {
            $train->levels()->attach(3);
        }
        return response()->json(['success' => 'cars Added Successfully'], 200);
    }
    public function addSeatsToCars(Train $train,Request $request)
    {
        for($i = 0; $i<$request->seatA; $i++)
        {
            $cars = $train->cars()->where('level_id',1)->get();
            $cars->each(function($item){
                $item->seats()->create(['status'=>'available']);
            });

        }

        for($i = 0; $i<$request->seatB; $i++)
        {
            $cars = $train->cars()->where('level_id',11)->get();
            $cars->each(function($item){
                $item->seats()->create(['status'=>'available']);
            });
        }

        for($i = 0; $i<$request->seatC; $i++)
        {
            $cars = $train->cars()->where('level_id',21)->get();
            $cars->each(function($item){
                $item->seats()->create(['status'=>'available']);
            });
        }
        return response()->json(['success' => 'Seats Added Successfully'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Train  $train
     * @return \Illuminate\Http\Response
     */
    public function show(Train $train)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Train  $train
     * @return \Illuminate\Http\Response
     */
    public function edit(Train $train)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Train  $train
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Train $train)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Train  $train
     * @return \Illuminate\Http\Response
     */
    public function destroy(Train $train)
    {
        //
    }
}
