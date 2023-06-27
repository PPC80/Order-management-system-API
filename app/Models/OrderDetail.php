<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class OrderDetail extends Model
{
    use HasApiTokens, HasFactory;

    protected $fillable = [
        'id_pedido',
        'id_producto',
        'cantidad',
        'suma_precio'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
