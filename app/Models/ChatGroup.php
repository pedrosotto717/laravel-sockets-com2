<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatGroup extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'is_group'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'chat_group_user');
    }

    public function messages()
    {
        return $this->hasMany(ChatMessage::class);
    }
}
