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

    public function product()
    {
        return $this->hasOne(Product::class);
    }
}
