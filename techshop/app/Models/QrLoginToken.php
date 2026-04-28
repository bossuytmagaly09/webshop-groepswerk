<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QrLoginToken extends Model
{
    protected $fillable = [
        'token',
        'user_id',
        'expires_at',
        'confirmed_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'confirmed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
