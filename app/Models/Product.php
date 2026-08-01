<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'nombre',
        'descripcion',
        'precio',
        'combo',
        'unidadCombo',
        'image',
    ];

    public function category()
    {
        return $this->belongsTo(Categories::class);
    }
}
