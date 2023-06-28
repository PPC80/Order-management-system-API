<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['id_cliente' => '1', 'estado' => 'pendiente', 'valor_total' => '525.16', 'modo_pago' => 'PCE', 'id_direccion' => '1'],
            ['id_cliente' => '2', 'estado' => 'pendiente', 'valor_total' => '1238.99', 'modo_pago' => 'PCE', 'id_direccion' => '2'],
            ['id_cliente' => '3', 'estado' => 'entregado', 'valor_total' => '55.00', 'modo_pago' => 'transferencia', 'id_direccion' => '3']
        ];

        Order::insert($data);
    }
}
