<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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

    public function getImageAttribute($value)
    {
        return $value
            ? Storage::disk('s3')->url($value)
            : null;
    }
}
