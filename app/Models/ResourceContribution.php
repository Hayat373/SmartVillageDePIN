<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceContribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'resource_type',
        'amount',
        'transaction_id',
        'demand_prediction',
        'allocation_recommendation',
        'hedera_token_tx'
        
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}