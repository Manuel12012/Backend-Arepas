<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['email', 'phone','name','delivery', 'total', 'longitude', 'latitude', 'status' ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
