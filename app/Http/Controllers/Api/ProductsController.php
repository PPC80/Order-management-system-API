<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductsController extends Controller
{
    public function index(){

        try{
            $products = Product::all();

            if ($products->isEmpty()) {
                return response()->json(['message' => 'No products available']);
            }

            return $products;

        } catch (\Exception $e){
            Log::error("Error loading products: " . $e->getMessage());
            return response()->json(['error' => 'Failed to load products'], 500);
        }
    }



    public function store(Request $request){

        $request->validate([
            'id_categoria' => 'required|integer|numeric|between:1,3',
            'nombre' => 'required|string',
            'detalle' => 'string',
            'stock' => 'required|integer|numeric|gte:0',
            'valor' => 'required|decimal:2|numeric|gte:0',
        ]);

        try{
            Product::create([
                'id_categoria' => $request->input('id_categoria'),
                'nombre_producto' => $request->input('nombre'),
                'detalle' => $request->input('detalle'),
                'stock_number' => $request->input('stock'),
                'valor_venta' => $request->input('valor')
            ]);

            return response()->json(['message' => "Product saved successfully"], 201);

        } catch (\Exception $e){
            Log::error("Error saving product: " . $e->getMessage());
            return response()->json(['error' => 'Failed to save product'], 500);
        }
    }



    public function search(Request $request){

        $keyword = $request->input('keyword');

        try{
            $products = DB::table('products')
                ->where('nombre_producto', 'like', '%'.$keyword.'%')
                ->orWhere('detalle', 'like', '%'.$keyword.'%')
                ->get();

            if($products->isEmpty() || $products == "" || $products == null){
            return response()->json(['message' => "Cannot find product"]);
            }

            return response()->json($products);

        } catch (\Exception $e){
            Log::error("Error searching product: " . $e->getMessage());
            return response()->json(['error' => 'Failed to search product'], 500);
        }
    }



    public function update(Request $request){

        $request->validate([
            'id' => 'required|integer|numeric|gte:1',
            'id_categoria' => 'required|integer|numeric|between:1,3',
            'nombre_producto' => 'required|string',
            'detalle' => 'string',
            'stock_number' => 'required|integer|numeric|gte:0',
            'valor_venta' => 'required|decimal:2|numeric|gte:0',
        ]);

        try{
            $product = Product::find($request->input('id'));

            if ($product) {
                $product->update([
                    'id_categoria' => $request->input('id_categoria'),
                    'nombre_producto' => $request->input('nombre_producto'),
                    'detalle' => $request->input('detalle'),
                    'stock_number' => $request->input('stock_number'),
                    'valor_venta' => $request->input('valor_venta')
                ]);
                return response()->json(['message' => "Product updated successfully"]);
            } else {
                return response()->json(['message' => 'Product not found'], 404);
            }

        } catch (\Exception $e){
            Log::error("Error updating product: " . $e->getMessage());
            return response()->json(['error' => 'Failed to update product'], 500);
        }
    }



    public function destroy(Request $request){

        $request->validate([
            'id' => 'required|integer|numeric|gte:1'
        ]);

        try{
            $product = Product::find($request->input('id'));

            if ($product) {
                $product->delete();
                return response()->json(['message' => 'Product deleted successfully']);
            } else {
                return response()->json(['message' => 'Product not found'], 404);
            }

        } catch (\Exception $e){
            Log::error("Error deleting product: " . $e->getMessage());
            return response()->json(['error' => 'Failed to delete product'], 500);
        }
    }
}
