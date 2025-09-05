<?php

namespace App\Services;

use App\Models\AIPrediction;
use App\Models\VillageStat;
use Illuminate\Support\Facades\Cache;

class AIPredictionService
{
    public function predictResourceNeeds($villageName, $resourceType)
    {
        // For now, let's create a simple prediction without historical data
        $prediction = $this->generateSimplePrediction($resourceType);

        // Save prediction
        $aiPrediction = AIPrediction::create([
            'village_name' => $villageName,
            'resource_type' => $resourceType,
            'predicted_demand' => $prediction['demand'],
            'predicted_supply' => $prediction['supply'],
            'allocation_recommendation' => $prediction['allocation'],
            'prediction_date' => now(),
            'accuracy_score' => $prediction['confidence'] * 100
        ]);

        return $aiPrediction;
    }

    protected function generateSimplePrediction($resourceType)
    {
        // Simple predictions for demo purposes
        $defaults = [
            'energy' => ['demand' => 100, 'supply' => 50, 'allocation' => 0.7, 'confidence' => 0.6],
            'bandwidth' => ['demand' => 50, 'supply' => 30, 'allocation' => 0.6, 'confidence' => 0.6],
            'water' => ['demand' => 200, 'supply' => 150, 'allocation' => 0.75, 'confidence' => 0.6],
            'storage' => ['demand' => 100, 'supply' => 80, 'allocation' => 0.8, 'confidence' => 0.6],
            'computing' => ['demand' => 20, 'supply' => 15, 'allocation' => 0.65, 'confidence' => 0.6],
        ];

        return $defaults[$resourceType] ?? $defaults['energy'];
    }

    public function getCurrentPredictions($villageName = null)
    {
        // For now, let's return some dummy data to avoid errors
        // Once we have real data, we can implement the proper logic
        $dummyPredictions = [
            'energy' => collect([AIPrediction::make([
                'village_name' => $villageName ?: 'Demo Village',
                'resource_type' => 'energy',
                'predicted_demand' => 150,
                'predicted_supply' => 120,
                'allocation_recommendation' => 0.8,
                'prediction_date' => now(),
                'accuracy_score' => 85
            ])]),
            'bandwidth' => collect([AIPrediction::make([
                'village_name' => $villageName ?: 'Demo Village',
                'resource_type' => 'bandwidth',
                'predicted_demand' => 75,
                'predicted_supply' => 60,
                'allocation_recommendation' => 0.75,
                'prediction_date' => now(),
                'accuracy_score' => 80
            ])]),
        ];

        return collect($dummyPredictions);

        // Original code (commented out until we fix the table issue):
        /*
        $cacheKey = 'ai_predictions_' . ($villageName ?: 'all');
        
        return Cache::remember($cacheKey, 3600, function () use ($villageName) {
            $query = AIPrediction::where('prediction_date', '>=', now()->subDay());
                
            if ($villageName) {
                $query->where('village_name', $villageName);
            }
            
            return $query->orderBy('prediction_date', 'desc')
                ->get()
                ->groupBy('resource_type');
        });
        */
    }
}