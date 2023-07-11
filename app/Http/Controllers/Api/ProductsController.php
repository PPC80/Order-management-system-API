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
                return response()->json(['message' => 'No products available'], 404);
            }

            return response()->json($products, 200);

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


    public function show(Request $request){

        $request->validate([
            'id' => 'required|integer|numeric|gte:1'
        ]);

        try{
            $product = DB::table('products')
                ->where('id', $request->input('id'))
                ->first();

            if($product){
                return response()->json($product, 200);
            } else {
                return response()->json(['message' => "Cannot find product"], 404);
            }

        } catch (\Exception $e){
            Log::error("Error searching product: " . $e->getMessage());
            return response()->json(['error' => 'Failed to search product'], 500);
        }
    }


    public function search(Request $request){

        $request->validate([
            'keyword' => 'required'
        ]);

        $keyword = $request->input('keyword');

        try{
            $products = DB::table('products')
                ->where('nombre_producto', 'like', '%'.$keyword.'%')
                ->orWhere('detalle', 'like', '%'.$keyword.'%')
                ->get();

            if($products->isEmpty() || $products == "" || $products == null){
            return response()->json(['message' => "Cannot find product"], 404);
            }

            return response()->json($products, 200);

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
                return response()->json(['message' => "Product updated successfully"], 202);
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
                 //Se llama al metodo para destruir las imagenes antes de borrar el producto
                $imageController = app(ImageController::class);
                $imageController->destroyAll($product->id);

                $product->delete();
                return response()->json(['message' => 'Product deleted successfully'], 204);
            } else {
                return response()->json(['message' => 'Product not found'], 404);
            }

        } catch (\Exception $e){
            Log::error("Error deleting product: " . $e->getMessage());
            return response()->json(['error' => 'Failed to delete product'], 500);
        }
    }
}
