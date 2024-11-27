<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    protected $fillable = [
        'client_id',
        'project_id',
        'user_id',
        'subtotal',
        'tax',
        'discount',
        'total',
        'status'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function services()
    {
        return $this->hasMany(QuoteService::class);
    }

    public function materials()
    {
        return $this->hasMany(QuoteMaterial::class);
    }
}