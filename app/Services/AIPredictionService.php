<?php

namespace App\Services;

use App\Models\AIPrediction;
use App\Models\VillageStat;
use Illuminate\Support\Facades\Cache;

class AIPredictionService
{
    public function predictResourceNeeds($villageName, $resourceType)
    {
        // Get historical data for better predictions
        $historicalData = VillageStat::where('village_name', $villageName)
            ->where('resource_type', $resourceType)
            ->orderBy('stat_date', 'desc')
            ->take(30)
            ->get();

        // Simple AI/ML simulation - in real implementation, use proper ML algorithms
        $prediction = $this->generatePrediction($historicalData, $resourceType);

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

    protected function generatePrediction($historicalData, $resourceType)
    {
        if ($historicalData->isEmpty()) {
            // Default predictions for new villages
            $defaults = [
                'energy' => ['demand' => 100, 'supply' => 50, 'allocation' => 0.7],
                'bandwidth' => ['demand' => 50, 'supply' => 30, 'allocation' => 0.6],
                'water' => ['demand' => 200, 'supply' => 150, 'allocation' => 0.75],
                'storage' => ['demand' => 100, 'supply' => 80, 'allocation' => 0.8],
                'computing' => ['demand' => 20, 'supply' => 15, 'allocation' => 0.65],
            ];

            $prediction = $defaults[$resourceType] ?? $defaults['energy'];
            $prediction['confidence'] = 0.6;
            
            return $prediction;
        }

        // Simple trend analysis
        $totalDemand = $historicalData->avg('total_contributed') * 1.2; // 20% growth assumption
        $totalSupply = $historicalData->avg('total_allocated') * 1.1; // 10% growth assumption
        
        // Add some randomness to simulate real prediction variability
        $demandVariation = rand(90, 110) / 100;
        $supplyVariation = rand(85, 115) / 100;
        
        $predictedDemand = $totalDemand * $demandVariation;
        $predictedSupply = $totalSupply * $supplyVariation;
        
        // Calculate allocation recommendation
        $shortage = max(0, $predictedDemand - $predictedSupply);
        $allocationRecommendation = $shortage > 0 ? min(1, $predictedSupply / $predictedDemand) : 1;
        
        // Confidence based on data quantity and variability
        $dataPoints = count($historicalData);
        $confidence = min(0.95, 0.6 + ($dataPoints * 0.01)); // Higher confidence with more data

        return [
            'demand' => round($predictedDemand, 2),
            'supply' => round($predictedSupply, 2),
            'allocation' => round($allocationRecommendation, 2),
            'confidence' => round($confidence, 2)
        ];
    }

    public function getCurrentPredictions($villageName = null)
    {
        $cacheKey = 'ai_predictions_' . ($villageName ?: 'all');
        
        return Cache::remember($cacheKey, 3600, function () use ($villageName) {
            $query = AIPrediction::with('villageStats')
                ->where('prediction_date', '>=', now()->subDay());
                
            if ($villageName) {
                $query->where('village_name', $villageName);
            }
            
            return $query->orderBy('prediction_date', 'desc')
                ->get()
                ->groupBy('resource_type');
        });
    }
}