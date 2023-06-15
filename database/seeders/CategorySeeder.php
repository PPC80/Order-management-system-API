<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['id' => '1', 'nombre_categoria' => 'Alimentos'],
            ['id' => '2', 'nombre_categoria' => 'Aseo'],
            ['id' => '3', 'nombre_categoria' => 'Electronicos']
        ];

        Category::insert($data);
    }
}
