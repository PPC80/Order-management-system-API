<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\CartDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Cart;

class CartDetailController extends Controller
{
    public function index(Request $request){

        $request->validate([
            'id' => 'required|integer|numeric|gte:1'
        ]);

        try{
            $id = $request->input('id');

            $results = DB::select("
                SELECT cd.id_producto, pr.nombre_producto, pr.detalle, pr.valor_venta, cd.cantidad, cd.suma_precio
                FROM cart_details cd
                JOIN products pr ON cd.id_producto = pr.id
                JOIN carts ca ON ca.id = cd.id_cart
                WHERE ca.id = :id
            ", ['id' => $id]);

            if (empty($results)) {
                return response()->json(['message' => 'Cart is empty']);
            }

            return response()->json($results, 200);

        } catch (\Exception $e){
            Log::error("Error loading cart products: " . $e->getMessage());
            return response()->json(['error' => 'Failed to load cart products'], 500);
        }
    }



    public function add(Request $request){

        $request->validate([
            'id_cart' => 'required|integer|numeric|gte:1',
            'id_producto' => 'required|integer|numeric|gte:1',
            'cantidad' => 'required|integer|numeric|gte:1'
        ]);

        try{
            $product = Product::find($request->input('id_producto'));

            if($product){

                $quantity = $request->input('cantidad');

                if($product->stock_number < $quantity){
                    return response()->json(['message' => 'Not enough stock'], 404);
                } else if ($product->stock_number == 0){
                    return response()->json(['message' => 'Product out of stock'], 404);
                }

                $price = $product->valor_venta;
                $total = $quantity * $price;

                $productExists = CartDetail::where('id_cart', $request->input('id_cart'))
                   ->where('id_producto', $request->input('id_producto'))
                   ->first();

                $cart = Cart::where('id', $request->input('id_cart'))->first();

                if($productExists){
                    $productExists->increment('cantidad', $quantity);
                    $productExists->increment('suma_precio', $total);

                    $product->decrement('stock_number', $quantity);

                } else {
                    CartDetail::create([
                        'id_cart' => $request->input('id_cart'),
                        'id_producto' => $request->input('id_producto'),
                        'cantidad' => $request->input('cantidad'),
                        'suma_precio' => $total
                    ]);

                    $product->decrement('stock_number', $quantity);
                }

                if($cart->valor_total == null){
                    $cart->update([
                        'valor_total' => $total
                    ]);
                } else {
                    $cart->increment('valor_total', $total);
                }

                return response()->json(['message' => "Product added to cart successfully"], 201);

            } else {
                return response()->json(['message' => 'Product not found'], 404);
            }

        } catch (\Exception $e){
            Log::error("Error adding product to cart: " . $e->getMessage());
            return response()->json(['error' => 'Failed to add product to cart'], 500);
        }
    }


    public function remove(Request $request){

        $request->validate([
            'id_cart' => 'required|integer|numeric|gte:1',
            'id_producto' => 'required|integer|numeric|gte:1',
            'cantidad' => 'required|integer|numeric|gte:1'
        ]);

        try{
            $product = Product::find($request->input('id_producto'));

            if($product){

                $quantity = $request->input('cantidad');

                $price = $product->valor_venta;
                $total = $quantity * $price;

                $productExists = CartDetail::where('id_cart', $request->input('id_cart'))
                   ->where('id_producto', $request->input('id_producto'))
                   ->first();

                $cart = Cart::where('id', $request->input('id_cart'))->first();

                if($productExists){
                    $productExists->decrement('cantidad', $quantity);
                    $productExists->decrement('suma_precio', $total);

                    //Si el valor total del carrito es null, solo se actualiza con el nuevo valor
                    if($cart->valor_total == null){
                        $cart->update([
                            'valor_total' => $total
                        ]);
                    //Si al restar el total, el valor total se hace 0 o menos de 0, no resta, solo actualiza
                    } else if ($cart->valor_total - $total <= 0){
                        $cart->update([
                            'valor_total' => 0.00
                        ]);
                    } else {
                        $cart->decrement('valor_total', $total);
                    }

                    if ($productExists->cantidad <= 0) {
                        $productExists->delete();
                    }

                    $product->increment('stock_number', $quantity);

                    return response()->json(['message' => "Product removed from cart successfully"], 204);
                } else {
                    return response()->json(['message' => 'Product not found in cart'], 404);
                }

            } else {
                return response()->json(['message' => 'Product not found'], 404);
            }

        } catch (\Exception $e){
            Log::error("Error adding product to cart: " . $e->getMessage());
            return response()->json(['error' => 'Failed to remove product from cart'], 500);
        }
    }
}
