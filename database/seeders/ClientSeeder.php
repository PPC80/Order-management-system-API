<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['id_user' => '1', 'nombres' => 'Pedro', 'apellidos' => 'Paez', 'telefono' => '0987748250'],
            ['id_user' => '2', 'nombres' => 'Pepe', 'apellidos' => 'Lopez', 'telefono' => '0987895835'],
            ['id_user' => '3', 'nombres' => 'Carlos', 'apellidos' => 'Fernandez', 'telefono' => '0986348005'],
            ['id_user' => '4', 'nombres' => 'Tiffany', 'apellidos' => 'Martinez', 'telefono' => '0987488104']
        ];

        Client::insert($data);
    }
}
