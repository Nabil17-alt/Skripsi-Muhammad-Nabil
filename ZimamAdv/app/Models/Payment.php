<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'tb_payments';

    protected $fillable = [
        'order_id',
        'payment_method_id',
        'bank_account_id',
        'amount',
        'installment_tenor',
        'installment_interest_fee',
        'installment_monthly_amount',
        'snap_token',
        'status',
        'transaction_id_gateway',
        'reference',
        'payment_proof_path',
        'raw_callback_log',
        'paid_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function method()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function installments()
    {
        return $this->hasMany(Installment::class);
    }
}
