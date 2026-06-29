<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'tb_orders';

    protected $fillable = [
        'user_id',
        'order_number',
        'shipping_address_id',
        'promo_id',
        'subtotal_amount',
        'discount_amount',
        'shipping_fee',
        'grand_total',
        'shipping_distance_km',
        'production_status',
        'payment_status',
        'delivery_method',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function shippingAddress()
    {
        return $this->belongsTo(Address::class, 'shipping_address_id');
    }
}
