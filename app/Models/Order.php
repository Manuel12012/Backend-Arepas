<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'email',
        'phone',
        'name',
        'delivery',
        'total',
        'longitude',
        'latitude',
        'status',
        'scheduled_for',
        'distance_km',
        'free_delivery',
        'delivery_cost',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
