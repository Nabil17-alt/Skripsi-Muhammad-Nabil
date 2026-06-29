<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $table = 'tb_addresses';

    protected $fillable = [
        'user_id',
        'label',
        'recipient_name',
        'phone',
        'full_address',
        'latitude',
        'longitude',
        'is_default',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
