<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliverySetting extends Model
{
    protected $fillable = [
        'store_latitude',
        'store_longitude',
        'free_radius_km',
        'delivery_cost'
    ];
}