<?php

namespace Database\Seeders;

use App\Models\CartDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CartDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['id_cart' => '1', 'id_producto' => '2', 'cantidad' => '5', 'suma_precio' => '5.75'],
            ['id_cart' => '1', 'id_producto' => '5', 'cantidad' => '2', 'suma_precio' => '1000.00'],
            ['id_cart' => '1', 'id_producto' => '3', 'cantidad' => '3', 'suma_precio' => '6.00'],
            ['id_cart' => '2', 'id_producto' => '1', 'cantidad' => '5', 'suma_precio' => '1.25'],
            ['id_cart' => '2', 'id_producto' => '4', 'cantidad' => '6', 'suma_precio' => '7205.94'],
            ['id_cart' => '3', 'id_producto' => '1', 'cantidad' => '10', 'suma_precio' => '2.50'],
            ['id_cart' => '3', 'id_producto' => '4', 'cantidad' => '7', 'suma_precio' => '8406.93'],
            ['id_cart' => '3', 'id_producto' => '5', 'cantidad' => '12', 'suma_precio' => '6000.00']
        ];

        CartDetail::insert($data);
    }
}
