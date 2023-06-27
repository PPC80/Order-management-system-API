<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Client;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Api\CartController;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function create(Request $request){

        $request->validate([
            'modo_pago' => 'required|string|alpha:ascii|max:255'
        ]);

        $id = Auth::id();

        try{
            $client = Client::where('id_user', $id)->first();
            $valor = Cart::where('id_user', $id)->first();
            $address = Address::where('id_cliente', $client->id)->first();

            if($valor == null){
                return response()->json(['message' => 'User does not have a cart'], 404);
            }

            //return response()->json(['message' => $id]);

            Order::create([
                'id_cliente' => $client->id,
                'estado' => 'pendiente',
                'valor_total' => $valor->valor_total,
                'modo_pago' => $request->input('modo_pago'),
                'id_direccion' => $address->id
            ]);

            // $apiUrl = route('api.cart.delete');
            // Http::delete($apiUrl);

            //TODO LLAMAR DE ALGUNA FORMA AL DELETE DE CARTS
            app('App\Http\Controllers\Api\CartController')->destroy();

            return response()->json(['message' => 'Order created successfully'], 201);

        } catch (\Exception $e){
            Log::error("Error creating order: " . $e->getMessage());
            return response()->json(['error' => 'Failed to create order'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
