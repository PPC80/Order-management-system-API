<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function cartDetails()
    {
        return $this->hasMany(CartDetail::class);
    }
}
