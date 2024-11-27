<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuoteMaterial extends Model
{
    protected $fillable = [
        'quote_id',
        'material_id', 
        'quantity',
        'unit_price',
        'total'
    ];

    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}