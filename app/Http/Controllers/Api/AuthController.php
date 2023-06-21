<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Client;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class AuthController extends Controller
{
    public function login(Request $request){

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try{
            $user = User::where('email', $request['email'])->first();
            $credentials = $request->only('email', 'password');

            if($user == NULL){
                return response()->json(['error' => 'Invalid email or password'], 401);
            }

            if (!$user->tokens->isEmpty()){
                return response()->json([
                    'message'=>'User is already authenticated.',
                    'code'=> 403
                ]);
            } else {
                if (Auth::attempt($credentials)) {
                    $user = Auth::user();
                    $token = $user->createToken('Personal Access Token')->plainTextToken;

                    return response()->json([
                        'message' => 'Successfully logged in',
                        'access_token' => $token,
                        'token_type' => 'Bearer',
                    ]);
                } else {
                    return response()->json(['error' => 'Invalid email or password'], 401);
                }
            }
        } catch (\Exception $e){
            Log::error("Error loggging in: " . $e->getMessage());
            return response()->json(['error' => 'Failed to log in'], 500);
        }
    }


    public function logout(Request $request){

        try{
            $user = $request->user();
            $user->tokens()->delete();
            return response()->json(['message' => 'Logged out successfully']);
        } catch (\Exception $e){
            Log::error("Error logging out: " . $e->getMessage());
            return response()->json(['error' => 'Failed to log out'], 500);
        }
    }


    public function register(Request $request){

        $validator = Validator::make($request->all(), [
            'nombres' => ['required', 'string', 'max:255'],
            'apellidos' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'telefono' => ['string', 'regex:/^09\d{8}$/', 'size:10'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            //password_confirmation
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try{
            $user = User::create([
                'idRole' => 3,
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password')),
            ]);

            $client = Client::create([
                'id_user' => $user->id,
                'nombres' => $request->input('nombres'),
                'apellidos' => $request->input('apellidos'),
                'telefono' => $request->input('telefono')
            ]);

            $token = $user->createToken('API Token')->plainTextToken;

            return response()->json([
                'client' => $client,
                'user' => $user,
                'token' => $token,
            ], 201);
        } catch (\Exception $e){
            Log::error("Error registering: " . $e->getMessage());
            return response()->json(['error' => 'Failed to register'], 500);
        }
    }


    public function registerAdmin(Request $request){

        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            //password_confirmation
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try{
            $user = User::create([
                'idRole' => 1,
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password')),
            ]);

            return response()->json([
                'user' => $user,
                'message' => 'Admin account created',
            ], 201);
        } catch (\Exception $e){
            Log::error("Error registering: " . $e->getMessage());
            return response()->json(['error' => 'Failed to register'], 500);
        }
    }


    public function registerEmployee(Request $request){

        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            //password_confirmation
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try{
            $user = User::create([
                'idRole' => 2,
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password')),
            ]);

            return response()->json([
                'user' => $user,
                'message' => 'Employee account created',
            ], 201);
        } catch (\Exception $e){
            Log::error("Error registering: " . $e->getMessage());
            return response()->json(['error' => 'Failed to register'], 500);
        }
    }


    public function delete($id){

        try{
            $user = User::find($id);
            $idRole = Auth::user()->idRole;

            if($idRole == 0 || $idRole == 1){
                if($user == null){
                    return response()->json(['response' => 'User does not exist']);
                } else if($user->idRole == 0){
                    return response()->json(['response' => 'Cannot delete that user']);
                } else if($user->idRole == 1 && $idRole == 1){
                    return response()->json(['response' => 'Admins cannot delete their own accounts']);
                } else {
                    //Cambia el valor de id_user a null antes de borrar al user para que no haya problemas de constraints
                    DB::table('clients')->where('id_user', $id)->update(['id_user' => null]);
                    $user->tokens()->delete();
                    $user ->delete();
                    return response()->json(['response' => 'User deleted']);
                }
            } else {
                return response()->json(['response' => 'Unauthorized Role']);
            }
        } catch (\Exception $e){
            Log::error("Error deleting account: " . $e->getMessage());
            return response()->json(['error' => 'Failed to delete account'], 500);
        }
    }
}
