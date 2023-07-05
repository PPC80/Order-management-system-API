<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Image extends Model
{
    use HasApiTokens, HasFactory;

    protected $fillable = [
        'product_id',
        'cloudinary_public_id',
        'cloudinary_url'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
