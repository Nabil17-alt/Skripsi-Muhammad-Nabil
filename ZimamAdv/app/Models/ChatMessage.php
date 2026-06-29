<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $table = 'tb_chat_messages';

    protected $touches = ['chat'];

    protected $fillable = [
        'chat_id',
        'sender_type',
        'sender_id',
        'message',
        'file_path',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }
}
