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

