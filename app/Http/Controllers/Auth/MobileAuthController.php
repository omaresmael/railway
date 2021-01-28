<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Middleware\Test;
use App\Http\Requests\Mobile\LoginRequest;
use App\Http\Requests\Mobile\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class MobileAuthController extends Controller
{
    public function __construct()
    {

        $this->middleware('token')->only('logout');

    }

    public function login(LoginRequest $request){
        if($this->validation($request))
        {
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }

            if($request->has('admin')){
                $token = $user->createToken($request->device_name,['admin']);
            }
            else {
                $token = $user->createToken($request->device_name);
            }

            return ['token'=>$token,'$user'=>$user->name];
        }
         return $this->validation($request);
    }

    public function register(RegisterRequest $request)

    {
            if($this->validation($request)){

                $user = $this->create($request->all());

                if($request->has('admin')){
                    $token = $user->createToken($request->device_name,['admin']);
                }
                else {
                    $token = $user->createToken($request->device_name);
                }

                return ['token'=>$token,'user'=>$user->name];
            }

        return $this->validation($request);


    }

    public function logout(Request $request)

    {

        $request->user()->currentAccessToken()->delete();
        return 'Done';
    }



    protected function validation($request)

    {
        $validator = Validator::make($request->all(),$request->rules());

        if ($validator->fails()) {

            //pass validator errors as errors object for ajax response

            return response()->json(['errors'=>$validator->errors()]);
        }
        return true;
    }

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

}
