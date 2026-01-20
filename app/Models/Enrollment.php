<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $fillable = [
        'user_id','cohort_id','status','enrolled_at',
    ];

    protected $casts = [
        'enrolled_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cohort()
    {
        return $this->belongsTo(Cohort::class);
    }

    public function installments()
    {
        return $this->hasMany(Installment::class)->orderBy('number');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
