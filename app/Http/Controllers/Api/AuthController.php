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

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try{
            $user = User::where('email', $request['email'])->first();
            $credentials = $request->only('email', 'password');

            if($user == NULL){
                return response()->json(['error' => 'Invalid email or password'], 401);
            }

            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $token = $user->createToken('Personal Access Token')->plainTextToken;

                return response()->json([
                    'message' => 'Successfully logged in',
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'user_role' => $user->idRole,
                    'user_id' => $user->id
                ]);
            } else {
                return response()->json(['error' => 'Invalid email or password'], 401);
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
            return response()->json(['message' => 'Logged out successfully'], 200);
        } catch (\Exception $e){
            Log::error("Error logging out: " . $e->getMessage());
            return response()->json(['error' => 'Failed to log out'], 500);
        }
    }



    public function indexAccounts(){

        $id = Auth::id();
        $role = '';

        try{
            $user = User::where('id', $id)->first();

            if($user->idRole == 0){
                $role = 1;
            } else if ($user->idRole == 1){
                $role = 2;
            } else {
                return response()->json(['message' => 'Not authorized'], 401);
            }

            $results = DB::select("
                SELECT id, email, created_at
                FROM users
                WHERE idRole = :idRole
                ", ['idRole' => $role]);

            if (empty($results)) {
                return response()->json(['message' => 'No accounts available']);
            }

            return response()->json($results, 200);
        } catch (\Exception $e){
            Log::error("Error fetching accounts: " . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch accounts'], 500);
        }
    }




    public function register(Request $request){

        $validator = Validator::make($request->all(), [
            'nombres' => ['required', 'string', 'max:255', 'alpha:ascii'],
            'apellidos' => ['required', 'string', 'max:255', 'alpha:ascii'],
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


    public function delete(Request $request){

        $validator = Validator::make($request->all(), [
            'id_user' => 'required|integer|numeric|gte:1'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try{
            $user = User::find($request->input('id_user'));
            $idRole = Auth::user()->idRole;

            if($idRole == 0 || $idRole == 1){
                if($user == null){
                    return response()->json(['message' => 'User does not exist']);
                } else if($user->idRole == 0){
                    return response()->json(['response' => 'Cannot delete that user']);
                } else if($user->idRole == 1 && $idRole == 1){
                    return response()->json(['message' => 'Admins cannot delete their own accounts']);
                } else {
                    //Cambia el valor de id_user a null antes de borrar al user para que no haya problemas de constraints
                    DB::table('clients')->where('id_user', $request->input('id_user'))->update(['id_user' => null]);
                    $user->tokens()->delete();
                    $user ->delete();
                    return response()->json(['message' => 'User deleted'], 204);
                }
            } else {
                return response()->json(['message' => 'Unauthorized Role'], 403);
            }
        } catch (\Exception $e){
            Log::error("Error deleting account: " . $e->getMessage());
            return response()->json(['error' => 'Failed to delete account'], 500);
        }
    }
}
