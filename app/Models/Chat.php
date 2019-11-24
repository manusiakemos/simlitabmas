<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $table = "chats";
    protected $primaryKey = "chat_id";

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
