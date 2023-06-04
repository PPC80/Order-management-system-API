<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'Pedro', 'lastname' => 'Paez', 'idRole' => 0, 'email' => 'pedro@gmail.com', 'password' => Hash::make(12345678)]
        ];

        User::insert($data);
    }
}
