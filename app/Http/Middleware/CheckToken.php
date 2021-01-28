<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckToken
{
    /**
     * Handle an incoming request.
     * Check for a validated token in the request
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        $token = DB::table('personal_access_tokens')
            ->where('token','=',hash('sha256',$request->bearerToken() ))->first();
        if(!$token){
            return response()->json(['error' => 'you\'re not authorized'], 401);
        }
        $user = User::find($token->tokenable_id);
        $user->withAccessToken($token);
        $request->merge(['user'=>$user]);

        return $next($request);
    }
}
