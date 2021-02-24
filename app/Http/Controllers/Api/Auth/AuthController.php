<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\User;

class AuthController extends Controller
{
    /**
     * This function handle user registration.
     *
     * @param  \Illuminate\Http\Request  $request 
     * @return mixed
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'                  =>  ['required'],
            'email'                 =>  ['required', 'email', 'unique:users'],
            'password'              =>  ['required', 'min:8', 'confirmed'],
            'password_confirmation' =>  ['required'],
        ]);

        User::create([
            'name'  => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        
        return response()->json(['message'=>'Register Successfully']);
    }
	
    /**
     * This function handle user login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed 
     */
	public function login(Request $request)
    {
        $request->validate([
			'email' => 'required|email',
			'password' => 'required',
			'device_name' => 'required',
		]);

		$user = User::where('email', $request->email)->first();

		if (! $user || ! Hash::check($request->password, $user->password)) {
			throw ValidationException::withMessages([
				'email' => ['The provided credentials are incorrect.'],
			]);
		}

		return response()->json([
				'token'=>$user->createToken($request->device_name)->plainTextToken
			]);
	}


    /**
     * logout the current user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed 
     */
	public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();   

		return response()->json(['message'=> 'logout successfully']);
	}


    /**
     * This function return auth user data.
     *
     * @param  \Illuminate\Http\Request  $request 
     * @return mixed $user
     */
    public function currentUser(Request $request)
    {
        return response()->json($request->user());
    }
	
}
