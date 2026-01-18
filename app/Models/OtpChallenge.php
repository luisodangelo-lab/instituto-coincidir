<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtpChallenge extends Model
{
    protected $fillable = [
        'user_id','purpose','code_hash','expires_at',
        'attempt_count','resend_count','used_at','invalidated_at',
        'created_ip'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
        'invalidated_at' => 'datetime',
    ];
}
