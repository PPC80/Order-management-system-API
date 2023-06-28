<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Client;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Api\CartController;
use App\Models\OrderDetail;

class OrderController extends Controller
{
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

            $order = Order::create([
                'id_cliente' => $client->id,
                'estado' => 'pendiente',
                'valor_total' => $valor->valor_total,
                'modo_pago' => $request->input('modo_pago'),
                'id_direccion' => $address->id
            ]);

            $cartDetails = DB::table('cart_details')
                ->where('id_cart', $valor->id)
                ->get();

            foreach ($cartDetails as $cartDetail) {
                DB::table('order_details')->insert([
                    'id_pedido' => $order->id,
                    'id_producto' => $cartDetail->id_producto,
                    'cantidad' => $cartDetail->cantidad,
                    'suma_precio' => $cartDetail->suma_precio
                ]);
            }

            //Se llama al metodo para destruir el carrito despues de hecho el pedido
            $cartController = app(CartController::class);
            $cartController->destroy();

            return response()->json(['message' => 'Order created successfully', 'order_id' => $order->id], 201);

        } catch (\Exception $e){
            Log::error("Error creating order: " . $e->getMessage());
            return response()->json(['error' => 'Failed to create order'], 500);
        }
    }



    public function index(){

        try{
            $results = DB::select("
                SELECT os.id, cl.nombres, cl.apellidos, ad.direccion, os.valor_total, os.modo_pago, os.estado
                FROM orders os
                JOIN addresses ad ON os.id_cliente = ad.id_cliente
                JOIN clients cl ON os.id_cliente = cl.id
                ");

            if (empty($results)) {
                return response()->json(['message' => 'No orders available']);
            }

            return response()->json($results, 200);

        } catch (\Exception $e){
            Log::error("Error loading orders: " . $e->getMessage());
            return response()->json(['error' => 'Failed to load orders'], 500);
        }
    }



    public function showClientOrders(){

        $id = Auth::id();

        try{
            $results = DB::select("
                SELECT os.id, cl.nombres, cl.apellidos, ad.direccion, os.valor_total, os.modo_pago, os.estado
                FROM orders os
                JOIN addresses ad ON os.id_cliente = ad.id_cliente
                JOIN clients cl ON os.id_cliente = cl.id
                WHERE cl.id_user = :id
            ", ['id' => $id]);


            if (empty($results)) {
                return response()->json(['message' => 'No orders available']);
            }

            return response()->json($results, 200);

        } catch (\Exception $e){
            Log::error("Error loading orders: " . $e->getMessage());
            return response()->json(['error' => 'Failed to load orders'], 500);
        }
    }



    public function search(Request $request){

        $request->validate([
            'keyword' => 'required'
        ]);

        $keyword = $request->input('keyword');

        try{
            //Busca si el keyword es igual a algun nombre/apellido/direccion en sus respectivas tablas
            $orders = DB::table('orders')
                ->select('orders.id', 'clients.nombres', 'clients.apellidos', 'addresses.direccion', 'orders.valor_total', 'orders.modo_pago', 'orders.estado')
                ->leftJoin('clients', 'orders.id_cliente', '=', 'clients.id')
                ->leftJoin('addresses', 'orders.id_cliente', '=', 'addresses.id_cliente')
                ->whereIn('orders.id_cliente', function ($query) use ($keyword) {
                    $query->select('id')
                        ->from('clients')
                        ->where('nombres', 'like', '%'.$keyword.'%')
                        ->orWhere('apellidos', 'like', '%'.$keyword.'%');
                })
                ->orWhereIn('orders.id_cliente', function ($query) use ($keyword) {
                    $query->select('id_cliente')
                        ->from('addresses')
                        ->where('direccion', 'like', '%'.$keyword.'%');
                })
                ->orWhere('orders.estado', 'like', '%'.$keyword.'%')
                ->orWhere('orders.modo_pago', 'like', '%'.$keyword.'%')
                ->get();

            if($orders->isEmpty() || $orders == "" || $orders == null){
                return response()->json(['message' => "Cannot find orders"], 404);
            } else {
                return response()->json($orders, 200);
            }

        } catch (\Exception $e){
            Log::error("Error searching orders: " . $e->getMessage());
            return response()->json(['error' => 'Failed to search orders'], 500);
        }
    }



    public function updateState(Request $request){

        $request->validate([
            'id_pedido' => 'required|integer|numeric|gte:1',
            'estado' => 'required|string|alpha:ascii|max:255'
        ]);

        $id = $request->input('id_pedido');

        try{
            $order = Order::find($id);

            if ($order) {
                $order->update([
                    'estado' => $request->input('estado')
                ]);
                return response()->json(['message' => "Order state updated successfully"], 202);
            } else {
                return response()->json(['message' => 'Order not found'], 404);
            }

        } catch (\Exception $e){
            Log::error("Error updating order state: " . $e->getMessage());
            return response()->json(['error' => 'Failed to update order state'], 500);
        }
    }



    public function destroy(Request $request){

        $request->validate([
            'id_user' => 'required|integer|numeric|gte:1'
        ]);

        $id = $request->input('id_user');

        try{
            $client = Client::where('id_user', $id)->first();
            $order = Order::where('id_cliente', $client->id)->first();

            if ($order) {
                OrderDetail::where('id_pedido', $order->id)->delete();
                $order->delete();
            } else {
                return response()->json(['message' => "Order does not exist"], 404);
            }

            return response()->json(['message' => "Order deleted successfully"]);

        } catch (\Exception $e){
            Log::error("Error deleting order: " . $e->getMessage());
            return response()->json(['error' => 'Failed to delete order'], 500);
        }
    }
}
