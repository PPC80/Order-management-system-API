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
            ['id_cart' => '1', 'id_producto' => '5', 'cantidad' => '2', 'suma_precio' => '1000.00']
        ];

        CartDetail::insert($data);
    }
}
