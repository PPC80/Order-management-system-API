<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['id_categoria' => '1', 'nombre_producto' => 'Chocolate', 'detalle' => 'Manicho', 'stock_number' => 20, 'valor_venta' => 0.25],
            ['id_categoria' => '1', 'nombre_producto' => 'Leche', 'detalle' => 'Vita', 'stock_number' => '60', 'valor_venta' => '1.15'],
            ['id_categoria' => '2', 'nombre_producto' => 'Papel Higienico', 'detalle' => 'Familia', 'stock_number' => '120', 'valor_venta' => '2'],
            ['id_categoria' => '3', 'nombre_producto' => 'TV Samsung', 'detalle' => '50 pulgadas', 'stock_number' => '5', 'valor_venta' => '1200.99'],
            ['id_categoria' => '3', 'nombre_producto' => 'Celular Nokia', 'detalle' => 'Nokia 10', 'stock_number' => '10', 'valor_venta' => '500']
        ];

        Product::insert($data);
    }
}
