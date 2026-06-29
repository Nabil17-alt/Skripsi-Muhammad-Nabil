<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'tb_products';

    protected $fillable = [
        'name',
        'slug',
        'category',
        'description',
        'base_price',
        'lead_time_days',
        'allow_custom_design',
        'allow_design_service',
        'design_service_fee',
        'is_active',
    ];

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
