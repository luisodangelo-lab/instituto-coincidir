<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'title', 'description',

        'type', 'modality', 'months', 'duration_weeks', 'hours_total',
        'is_active',

        'expediente_number', 'resolution_number', 'presentation_date', 'ministry_approved',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'ministry_approved' => 'boolean',
        'presentation_date' => 'date',
        'months' => 'integer',
        'duration_weeks' => 'integer',
        'hours_total' => 'integer',
    ];

    public function cohorts()
    {
        return $this->hasMany(Cohort::class);
    }
}
