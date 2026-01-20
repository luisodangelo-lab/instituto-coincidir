<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'user_id','enrollment_id',
        'type','provider','method',
        'amount','status','reference',
        'receipt_path','paid_at',
        'created_by','approved_by','approved_at',
        'refunded_by','refunded_at','refund_of_payment_id',
        'notes',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'approved_at' => 'datetime',
        'refunded_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function allocations()
    {
        return $this->hasMany(PaymentAllocation::class);
    }

    public function refundOf()
    {
        return $this->belongsTo(Payment::class, 'refund_of_payment_id');
    }
}
