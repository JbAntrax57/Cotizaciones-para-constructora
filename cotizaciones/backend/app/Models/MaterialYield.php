<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialYield extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_id',
        'application_type',
        'yield_per_unit',
        'unit_measure',
        'notes'
    ];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
} 