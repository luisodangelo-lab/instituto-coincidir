<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentAllocation extends Model
{
    protected $table = 'payment_allocations';

    // Permitir create([...]) sin problemas
    protected $guarded = [];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function installment()
    {
        return $this->belongsTo(Installment::class);
    }
}
