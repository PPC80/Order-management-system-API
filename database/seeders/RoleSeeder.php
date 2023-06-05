<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $data = [
            ['id' => '0', 'descripcion' => 'superadministrador'],
            ['id' => '1', 'descripcion' => 'administrador'],
            ['id' => '2', 'descripcion' => 'empleado'],
            ['id' => '3', 'descripcion' => 'cliente'],
        ];

        Role::insert($data);
    }
}
