<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConstructionTypeService extends Model
{
    use HasFactory;

    protected $fillable = [
        'construction_type_id',
        'service_id',
        'work_type',
        'rate_per_unit',
        'unit_measure',
        'calculation_formula',
        'is_required',
        'sort_order',
        'notes'
    ];

    protected $casts = [
        'rate_per_unit' => 'decimal:2',
        'is_required' => 'boolean',
        'sort_order' => 'integer'
    ];

    public function constructionType()
    {
        return $this->belongsTo(ConstructionType::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
} 