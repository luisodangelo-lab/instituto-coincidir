<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletMovement extends Model
{
    protected $fillable = [
        'user_id','payment_id','amount','reason','notes','created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
