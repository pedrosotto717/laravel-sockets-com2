<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'chat_group_id',
        'message',
        'file_path'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function chatGroup()
    {
        return $this->belongsTo(ChatGroup::class);
    }
}
