<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show(){

        try{
            $id = Auth::id();

            $result = DB::select("
                SELECT cl.id, cl.id_user, cl.nombres, cl.apellidos, cl.telefono, ad.direccion
                FROM clients cl
                LEFT JOIN addresses ad ON cl.id = ad.id_cliente
                JOIN users us ON cl.id = us.id
                WHERE cl.id_user = :id
            ", ['id' => $id]);

            if (empty($result)) {
                return response()->json(['message' => 'No profile data found']);
            }

            return response()->json($result, 200);

        } catch (\Exception $e){
            Log::error("Error loading profile: " . $e->getMessage());
            return response()->json(['error' => 'Failed to load profile'], 500);
        }
    }


    public function update(Request $request){

        $request->validate([
            'nombres' => ['required', 'string', 'max:255', 'alpha:ascii'],
            'apellidos' => ['required', 'string', 'max:255', 'alpha:ascii'],
            'telefono' => ['string', 'regex:/^09\d{8}$/', 'size:10'],
            'direccion' => ['string', 'max:255']
        ]);

        try{
            $id = Auth::id();
            $client = Client::find($id);
            $address = Address::where('id_cliente', $id)->first();

            if ($client) {
                $client->update([
                    'nombres' => $request->input('nombres'),
                    'apellidos' => $request->input('apellidos'),
                    'telefono' => $request->input('telefono')
                ]);

                if($address){
                    $address->update([
                        'direccion' => $request->input('direccion'),
                    ]);
                } else {
                    Address::create([
                        'id_cliente' => $id,
                        'direccion' => $request->input('direccion')
                    ]);
                }

                return response()->json(['message' => "Profile updated successfully"], 202);
            } else {
                return response()->json(['message' => 'Profile not found'], 404);
            }

        } catch (\Exception $e){
            Log::error("Error updating profile: " . $e->getMessage());
            return response()->json(['error' => 'Failed to update profile'], 500);
        }
    }
}
