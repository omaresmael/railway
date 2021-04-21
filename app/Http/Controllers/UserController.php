<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller

{
    public function __construct()
    {
        return $this->middleware(['token']);
    }


    public function index()
    {
        $user = \request()->user;
        if($user->isAdmin()){
            $users = User::all();
            return responseFormat($users);
        }
        return response()->json(['error' => 'you\'re not authorized'], 401);

    }

    public function destroy(User $deletedUser){

        $user = \request()->user;

        if($user->isAdmin()){
            $deletedUser->tokens()->delete();
            $deletedUser->delete();
            return response()->json(['success' => 'User Deleted Successfully'], 200);
        }
        return response()->json(['error' => 'you\'re not authorized'], 401);
    }

    public function addToWallet(Request $request)
    {
        $user = \request()->user;
        if($user->isAdmin())
        {
            $request->validate([
                'wallet' =>'required|numeric',
                'user_id'=>'required'
            ]);
            $normalUser = User::find($request->user_id);
            if($normalUser)
            {
                $normalUser->wallet += $request->wallet;
                $normalUser->update();
            };

            return response()->json(['success' =>'The amount of money has been added successfully'],200);
        }
        else
        {
            return response()->json(['error' => 'you\'re not authorized'], 401);
        }
    }
}
