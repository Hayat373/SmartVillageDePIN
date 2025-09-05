<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceContribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'resource_type', 'amount', 'transaction_id', 'status', 'ai_analysis',
    ];

    protected $casts = [
        'amount' => 'decimal:6',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

     public function allocations()
    {
        return $this->hasMany(ResourceAllocation::class, 'contribution_id');
    }

     public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

}