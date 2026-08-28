<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $table = 'offer';

    protected $fillable = [
        'inicio',
        'fin',
        'descuento',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
