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
Route::post('add_cars/{train}',[\App\Http\Controllers\TrainController::class,'addCarsToTrain']);
Route::post('add_seats/{train}',[\App\Http\Controllers\TrainController::class,'addSeatsToCars']);

//tickets
Route::get('/tickets/',[\App\Http\Controllers\SeatController::class,'getTicket']);
Route::post('/tickets/',[\App\Http\Controllers\SeatController::class,'bookTicket']);
Route::delete('/tickets/{seat}',[\App\Http\Controllers\SeatController::class,'deleteTicket']);


//user Handler
Route::get('/users',[\App\Http\Controllers\UserController::class,'index']);
Route::post('/wallet/',[\App\Http\Controllers\UserController::class,'addToWallet']);
Route::delete('/users/{deletedUser}',[\App\Http\Controllers\UserController::class,'destroy']);


