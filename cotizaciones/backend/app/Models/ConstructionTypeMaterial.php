<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConstructionTypeMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'construction_type_id',
        'material_id',
        'application_type',
        'quantity_per_unit',
        'unit_measure',
        'calculation_formula',
        'is_required',
        'sort_order',
        'notes'
    ];

    protected $casts = [
        'quantity_per_unit' => 'decimal:4',
        'is_required' => 'boolean',
        'sort_order' => 'integer'
    ];

    public function constructionType()
    {
        return $this->belongsTo(ConstructionType::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
} 