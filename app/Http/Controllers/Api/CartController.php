<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function create(){

        $id = Auth::id();

        try{
            $cartExists = Cart::where('id_user', $id)->exists();

            if($cartExists){
                return response()->json(['message' => "Only one cart per account can be active at a time"], 409);
            } else {
                $cart = Cart::create([
                    'id_user' => $id
                ]);
            }

            return response()->json(['message' => "Cart saved successfully", 'cart id' => $cart->id], 201);

        } catch (\Exception $e){
            Log::error("Error saving cart: " . $e->getMessage());
            return response()->json(['error' => 'Failed to save cart'], 500);
        }
    }


    public function destroy(){

        $id = Auth::id();

        try{
            $cart = DB::table('carts')
                ->where('id_user', $id)
                ->first();

            if ($cart) {
                DB::table('cart_details')
                    ->where('id_cart', $cart->id)
                    ->delete();

                DB::table('carts')
                    ->where('id', $cart->id)
                    ->delete();
            } else {
                return response()->json(['message' => "Cart does not exist"], 404);
            }

            return response()->json(['message' => "Cart deleted successfully"]);

        } catch (\Exception $e){
            Log::error("Error deleting cart: " . $e->getMessage());
            return response()->json(['error' => 'Failed to delete cart'], 500);
        }
    }


    public function find(){

        $id = Auth::id();

        try{
            $cart = DB::table('carts')
                ->where('id_user', $id)
                ->first();

            if ($cart) {
                return response()->json(['cart_id' => $cart->id], 200);
            } else {
                return response()->json(['message' => "User does not have an active cart"], 404);
            }

        } catch (\Exception $e){
            Log::error("Error finding cart: " . $e->getMessage());
            return response()->json(['error' => 'Failed to find cart'], 500);
        }
    }
}
