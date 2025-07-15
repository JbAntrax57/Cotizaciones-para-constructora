<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaborRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_type',
        'description',
        'rate_per_unit',
        'unit_measure',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];
} 