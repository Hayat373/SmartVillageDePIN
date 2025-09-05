<?php

namespace App\Services;

use App\Models\ResourceContribution;
use Carbon\Carbon;

class AIPredictionService
{
    /**
     * Analyze resource contributions and predict demand
     */
    public function predictDemand($resourceType, $amount)
    {
        // Get historical data for the same resource type
        $historicalData = ResourceContribution::where('resource_type', $resourceType)
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->get();
            
        // Calculate total contributed in the last 30 days
        $totalContributed = $historicalData->sum('amount');
        
        // Calculate average daily contribution
        $averageDaily = $totalContributed > 0 ? $totalContributed / 30 : 0;
        
        // Get recent trend (last 7 days vs previous 7 days)
        $recentWeek = ResourceContribution::where('resource_type', $resourceType)
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->sum('amount');
            
        $previousWeek = ResourceContribution::where('resource_type', $resourceType)
            ->whereBetween('created_at', [Carbon::now()->subDays(14), Carbon::now()->subDays(7)])
            ->sum('amount');
            
        // Calculate trend (positive or negative)
        $trend = $previousWeek > 0 ? ($recentWeek - $previousWeek) / $previousWeek : 0;
        
        // Consider seasonal factors (simplified for demo)
        $seasonFactor = $this->getSeasonalFactor($resourceType);
        
        // Predict demand based on these factors
        $prediction = $this->calculatePrediction($averageDaily, $trend, $seasonFactor, $amount);
        
        // Generate allocation recommendation
        $recommendation = $this->generateRecommendation($resourceType, $prediction, $amount);
        
        return [
            'prediction' => $prediction,
            'recommendation' => $recommendation,
            'historical_data' => [
                'total_30_days' => $totalContributed,
                'average_daily' => $averageDaily,
                'trend' => $trend * 100, // as percentage
            ]
        ];
    }
    
    /**
     * Simplified seasonal factor (would be more complex in real application)
     */
    private function getSeasonalFactor($resourceType)
    {
        $month = date('n');
        
        // These factors would be based on historical data for your region
        if (in_array($month, [12, 1, 2])) { // Summer months in Southern Africa
            $factors = [
                'energy' => 1.2,  // Higher energy demand in summer
                'water' => 0.8,   // Lower water demand in summer (rainy season)
                'bandwidth' => 1.0,
                'storage' => 1.0
            ];
        } elseif (in_array($month, [6, 7, 8])) { // Winter months
            $factors = [
                'energy' => 0.9,  // Lower energy demand in winter
                'water' => 1.3,   // Higher water demand in winter (dry season)
                'bandwidth' => 1.0,
                'storage' => 1.0
            ];
        } else { // Spring/Autumn
            $factors = [
                'energy' => 1.0,
                'water' => 1.0,
                'bandwidth' => 1.0,
                'storage' => 1.0
            ];
        }
        
        return $factors[$resourceType] ?? 1.0;
    }
    
    /**
     * Calculate prediction based on multiple factors
     */
    private function calculatePrediction($averageDaily, $trend, $seasonFactor, $currentAmount)
    {
        // Base prediction on historical average adjusted for trend and season
        $predictedNeed = $averageDaily * (1 + $trend) * $seasonFactor;
        
        // If no historical data, use a simple threshold-based prediction
        if ($averageDaily == 0) {
            if ($currentAmount < 10) {
                return 'high';
            } elseif ($currentAmount < 50) {
                return 'medium';
            } else {
                return 'low';
            }
        }
        
        // Compare predicted need with current contribution
        $ratio = $currentAmount / max($predictedNeed, 0.1); // Avoid division by zero
        
        if ($ratio < 0.7) {
            return 'high';
        } elseif ($ratio < 1.3) {
            return 'medium';
        } else {
            return 'low';
        }
    }
    
    /**
     * Generate allocation recommendation based on prediction
     */
    private function generateRecommendation($resourceType, $prediction, $amount)
    {
        $recommendations = [
            'energy' => [
                'high' => "Demand is high. Consider distributing $amount kWh primarily to essential services (healthcare, education) and implement energy saving measures.",
                'medium' => "Demand is moderate. Allocate $amount kWh across residential and commercial needs with priority during peak hours.",
                'low' => "Demand is low. Store excess $amount kWh in community batteries for future high-demand periods."
            ],
            'bandwidth' => [
                'high' => "Demand is high. Prioritize $amount Mbps for educational content and healthcare services during peak hours.",
                'medium' => "Demand is moderate. Distribute $amount Mbps evenly across the village with quality of service for essential applications.",
                'low' => "Demand is low. Use $amount Mbps for community WiFi expansion and digital literacy programs."
            ],
            'water' => [
                'high' => "Demand is high. Allocate $amount liters primarily for drinking and sanitation, with careful rationing for other uses.",
                'medium' => "Demand is moderate. Distribute $amount liters across household, agricultural, and commercial needs.",
                'low' => "Demand is low. Store $amount liters in reserve tanks for future dry periods and expand irrigation for community gardens."
            ],
            'storage' => [
                'high' => "Demand is high. Prioritize $amount GB for critical community data, healthcare records, and educational resources.",
                'medium' => "Demand is moderate. Allocate $amount GB across various community needs with backup systems in place.",
                'low' => "Demand is low. Use $amount GB for archiving historical data and expanding digital library resources."
            ]
        ];
        
        return $recommendations[$resourceType][$prediction] ?? "Allocate based on current community needs.";
    }
}