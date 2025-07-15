<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuoteService extends Model
{
    use HasFactory;

    protected $fillable = [
        'quote_id',
        'service_id',
        'quantity',
        'unit_price',
        'total'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'total' => 'decimal:2'
    ];

    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}