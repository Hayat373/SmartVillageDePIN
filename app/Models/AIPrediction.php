<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AIPrediction extends Model
{
    use HasFactory;

    // Explicitly define the table name
    protected $table = 'ai_predictions'; // Use the actual table name

    protected $fillable = [
        'village_name', 'resource_type', 'predicted_demand',
        'predicted_supply', 'allocation_recommendation',
        'prediction_date', 'accuracy_score'
    ];

    protected $casts = [
        'predicted_demand' => 'decimal:6',
        'predicted_supply' => 'decimal:6',
        'allocation_recommendation' => 'decimal:6',
        'accuracy_score' => 'decimal:2',
        'prediction_date' => 'date',
    ];

    public function scopeRecent($query, $days = 7)
    {
        return $query->where('prediction_date', '>=', now()->subDays($days));
    }

    public function scopeForVillage($query, $villageName)
    {
        return $query->where('village_name', $villageName);
    }
}