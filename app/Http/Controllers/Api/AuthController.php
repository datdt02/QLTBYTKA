<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    // public function login(Request $request){
    //     $loginDetails = $request -> only('email', 'password');
    //     if(Auth::attempt($loginDetails)){
    //         return response()->json(['message' => 'Login successful', 'code' => 200]);
    //     } else {
    //         return response()->json(['message' => 'Invalid email or password', 'code' => 501]);
    //     }
    // }
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'email|required',
                'password' => 'required'
            ]);
            dd(123);

            $credentials = request(['email', 'password']);

            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'status_code' => 400,
                    'message' => 'Unauthorized'
                ], 400);
            }
            $user = User::where('email', $request->email)->first();

            if (!Hash::check($request->password, $user->password, [])) {
                throw new \Exception('Error in Login');
            }

            $tokenResult = $user->createToken('authToken')->plainTextToken;

            return response()->json([
                'data' => $user,
                'status_code' => 200,
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Error in Login',
                'error' => $error,
            ], 500);
        }
    }

    public function logout()
    {
        Auth()->user()->tokens()->delete();
        return response()->json([
            'status_code' => 200,
            'message' => 'Logout Successful',
        ]);

    }
}
