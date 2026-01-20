<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'code','title','description','type','is_active',
    ];

    public function cohorts()
    {
        return $this->hasMany(Cohort::class);
    }
}
