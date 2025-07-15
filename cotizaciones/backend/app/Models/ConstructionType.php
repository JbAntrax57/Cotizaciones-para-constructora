<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConstructionType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'default_specs'
    ];

    protected $casts = [
        'default_specs' => 'array'
    ];

    public function materials()
    {
        return $this->hasMany(ConstructionTypeMaterial::class);
    }

    public function services()
    {
        return $this->hasMany(ConstructionTypeService::class);
    }
} 