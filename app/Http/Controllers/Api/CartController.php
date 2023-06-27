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
            $cart = Cart::create([
                'id_user' => $id
            ]);

            return response()->json(['message' => "Cart saved successfully", 'cart id' => $cart->id], 201);

        } catch (\Exception $e){
            Log::error("Error saving cart: " . $e->getMessage());
            return response()->json(['error' => 'Failed to save cart'], 500);
        }
    }


    public function destroy(){

        $id = Auth::id();

        try{
            $cart_details = DB::table('cart_details')
                ->where('id_cart', $id)
                ->delete();

            $cart = Cart::find($id);

            if ($cart) {
                $cart->delete();
            }

            return response()->json(['message' => "Cart deleted successfully"]);

        } catch (\Exception $e){
            Log::error("Error deleting cart: " . $e->getMessage());
            return response()->json(['error' => 'Failed to delete cart'], 500);
        }
    }
}
