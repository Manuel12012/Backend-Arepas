<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
        "offer_id"
    ];

    public function category()
    {
        return $this->belongsTo(Categories::class);
    }

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }


    public function getImageAttribute($value)
    {
        return $value
            ? Storage::disk('s3')->url($value)
            : null;
    }
}
