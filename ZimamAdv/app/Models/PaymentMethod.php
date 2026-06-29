<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $table = 'tb_payment_methods';

    protected $fillable = [
        'name',
        'type',
        'is_active',
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
