<?php

namespace Database\Seeders;

use App\Models\Image;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['product_id' => '1', 'cloudinary_public_id' => 'i2pw7kgpe72yjg4fnkdd', 'cloudinary_url' => 'https://res.cloudinary.com/dsw1ymmh9/image/upload/v1689212842/i2pw7kgpe72yjg4fnkdd.jpg'],
            ['product_id' => '2', 'cloudinary_public_id' => 'tmsvnfizxf99qvcvoarg', 'cloudinary_url' => 'https://res.cloudinary.com/dsw1ymmh9/image/upload/v1689150319/tmsvnfizxf99qvcvoarg.jpg'],
            ['product_id' => '3', 'cloudinary_public_id' => 'v5kkocc3pobelddrghoz', 'cloudinary_url' => 'https://res.cloudinary.com/dsw1ymmh9/image/upload/v1689150334/v5kkocc3pobelddrghoz.png'],
            ['product_id' => '4', 'cloudinary_public_id' => 'kvngm87gfiw6u3gsel49', 'cloudinary_url' => 'https://res.cloudinary.com/dsw1ymmh9/image/upload/v1689212879/kvngm87gfiw6u3gsel49.webp'],
            ['product_id' => '5', 'cloudinary_public_id' => 'npt9csjeuxytjgjxbxhn', 'cloudinary_url' => 'https://res.cloudinary.com/dsw1ymmh9/image/upload/v1689212704/npt9csjeuxytjgjxbxhn.jpg']
        ];

        Image::insert($data);
    }
}
