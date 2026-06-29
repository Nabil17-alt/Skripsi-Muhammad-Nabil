<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $table = 'tb_product_variants';

    protected $fillable = [
        'product_id',
        'size',
        'material',
        'price',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
