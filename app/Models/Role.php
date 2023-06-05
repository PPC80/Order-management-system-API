<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Role extends Model
{
    use HasApiTokens, HasFactory;

    protected $fillable = ['id', 'descripcion'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
