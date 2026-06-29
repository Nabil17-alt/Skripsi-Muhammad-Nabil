<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;

    protected $table = 'tb_bank_accounts';

    protected $fillable = [
        'payment_method_id',
        'bank_name',
        'account_number',
        'account_holder',
        'image_path',
        'is_active',
    ];

    public function method()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }
}
