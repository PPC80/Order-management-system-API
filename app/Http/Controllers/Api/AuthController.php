<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request['email'])->first();
        $credentials = $request->only('email', 'password');

        if (!$user->tokens->isEmpty())
        {
            return response()->json([
                'message'=>'User is already authenticated.',
                'code'=> 403
            ]);
        } else {
            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $token = $user->createToken('Personal Access Token')->plainTextToken;

                return response()->json([
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    //'user' => $user
                ]);
            } else {
                return response()->json(['error' => 'Invalid email or password'], 401);
            }
        }
    }


    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }
}
