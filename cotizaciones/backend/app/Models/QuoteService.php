<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuoteService extends Model
{
    protected $fillable = [
        'quote_id',
        'service_id',
        'quantity',
        'unit_price',
        'total'
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