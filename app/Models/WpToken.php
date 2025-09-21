<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WpToken extends Model
{
    protected $fillable = ['access_token', 'refresh_token', 'expires_at', 'blog_id', 'user_id'];
    protected $casts = ['expires_at' => 'datetime'];

    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }
}
