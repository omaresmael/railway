<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('token')->get('/user', function (Request $request) {
    return  $request->user;
});

Route::post('/token/login',[App\Http\Controllers\Auth\MobileAuthController::class, 'login'])->name('login');
Route::post('/token/register',[App\Http\Controllers\Auth\MobileAuthController::class, 'register'])->name('register');
Route::post('/token/logout',[App\Http\Controllers\Auth\MobileAuthController::class, 'logout']);

//trips
Route::resource('trips',App\Http\Controllers\TripController::class);

//stations
Route::resource('stations',\App\Http\Controllers\StationController::class);

//trains
Route::resource('trains',\App\Http\Controllers\TrainController::class);

//tickets
Route::get('/tickets/{seat}',[\App\Http\Controllers\SeatController::class,'getTicket']);
Route::post('/tickets/{seat}',[\App\Http\Controllers\SeatController::class,'bookTicket']);


//user Handler
Route::get('/users',[\App\Http\Controllers\UserController::class,'index']);
Route::delete('/users/{deletedUser}',[\App\Http\Controllers\UserController::class,'destroy']);

