<?php

namespace Database\Seeders;

use App\Models\Cart;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['id_user' => '1', 'valor_total' => '525.16'],
            ['id_user' => '2', 'valor_total' => '1238.99'],
            ['id_user' => '3', 'valor_total' => '55.00']
        ];

        Cart::insert($data);
    }
}
