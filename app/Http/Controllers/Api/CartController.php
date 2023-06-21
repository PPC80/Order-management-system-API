<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    public function create(Request $request){

        $request->validate([
            'id_user' => 'required|integer|numeric|gte:1|unique:carts'
        ]);

        try{
            $cart = Cart::create([
                'id_user' => $request->input('id_user')
            ]);

            return response()->json(['message' => "Cart saved successfully", 'cart id' => $cart->id], 201);

        } catch (\Exception $e){
            Log::error("Error saving cart: " . $e->getMessage());
            return response()->json(['error' => 'Failed to save cart'], 500);
        }
    }


    public function destroy(Request $request){

       $request->validate([
            'id' => 'required|integer|numeric|gte:1'
        ]);

        try{
            $cart_details = DB::table('cart_details')
                ->where('id_cart', $request->input('id'))
                ->delete();

            $cart = Cart::find($request->input('id'));

            if ($cart) {
                $cart->delete();
            }

            return response()->json(['message' => "Cart deleted successfully"], 204);

        } catch (\Exception $e){
            Log::error("Error deleting cart: " . $e->getMessage());
            return response()->json(['error' => 'Failed to delete cart'], 500);
        }
    }
}
