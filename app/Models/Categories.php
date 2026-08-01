<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    protected $fillable = [
        'nombre',
        'image',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
