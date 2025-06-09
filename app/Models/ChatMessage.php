<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ChatMessage extends Model
{
    protected $fillable = ['room', 'user_id', 'message'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

