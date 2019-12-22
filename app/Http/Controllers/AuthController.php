<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;

class AuthController extends Controller
{
  
    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @return [integer] user_id
     * @return [string] token
     * @return [json] status
     */
    public function login(Request $request)
    {
                // Validation
        $validator = \Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'status' => [
                    'code' => 422,
                    'message' => 'Validation failed'
                ]
            ]);
        }

        $credentials = request(['email', 'password']);

        if(!Auth::guard('web')->attempt($credentials))
            return response()->json([
                'status' => [
	            	'code' => 401,
	            	'message' => 'Unauthorized',
	            ]
            ]);

        $user = User::where('email',$request->email)->first();

        $tokenResult = $user->createToken('newsoft')->accessToken;

        return response()->json([
        	'user_id' => $user->id,
            'token' => $tokenResult,
            'status' => [
            	'code' => 200,
            	'message' => 'Access Granted',
            ]
        ]);
    }
  
    /**
     * Logout user (Revoke the token)
     *
     * @return [json] status
     */
    public function logout(Request $request)
    {
        auth()->user()->token()->revoke();

        return response()->json([
        	'status' => [
            	'code' => 200,
            	'message' => 'Successfully logged out'
            ]
        ]);
    }
  
    /**
     * Get the authenticated User
     *
     * @return [json] user object
     * @return [json] status
     */
    public function user(Request $request)
    {
        return response()->json([
        	'user' => auth()->user(),
        	'status' => [
            	'code' => 200,
            	'message' => 'Success'
            ]
        ]);
    }
}