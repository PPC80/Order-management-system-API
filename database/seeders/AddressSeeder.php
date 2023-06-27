<?php

namespace Database\Seeders;

use App\Models\Address;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['id_cliente' => '1', 'direccion' => 'Av. 10 de Agosto'],
            ['id_cliente' => '2', 'direccion' => 'Av. de la Prensa'],
            ['id_cliente' => '3', 'direccion' => 'Av. Shyris'],
            ['id_cliente' => '4', 'direccion' => 'Av. Amazonas'],
        ];

        Address::insert($data);
    }
}
