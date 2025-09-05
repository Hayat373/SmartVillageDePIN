<?php

namespace App\Http\Controllers\AI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AIPredictionService;
use App\Models\ResourceContribution;

class AIAnalysisController extends Controller
{
    protected $aiService;

    public function __construct(AIPredictionService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function index()
    {
        // Get summary of all contributions
        $totalContributions = ResourceContribution::count();
        $totalEnergy = ResourceContribution::where('resource_type', 'energy')->sum('amount');
        $totalBandwidth = ResourceContribution::where('resource_type', 'bandwidth')->sum('amount');
        $totalWater = ResourceContribution::where('resource_type', 'water')->sum('amount');
        $totalStorage = ResourceContribution::where('resource_type', 'storage')->sum('amount');
        
        // Get predictions for each resource type
        $predictions = [];
        $resourceTypes = ['energy', 'bandwidth', 'water', 'storage'];
        
        foreach ($resourceTypes as $type) {
            $recentAmount = ResourceContribution::where('resource_type', $type)
                ->where('created_at', '>=', now()->subDays(7))
                ->sum('amount');
                
            // If no recent data, use a default amount for prediction
            if ($recentAmount <= 0) {
                $recentAmount = 10; // Default value for demonstration
            }
            
            try {
                $predictions[$type] = $this->aiService->predictDemand($type, $recentAmount);
            } catch (\Exception $e) {
                $predictions[$type] = [
                    'prediction' => 'unknown',
                    'recommendation' => 'Error in prediction: ' . $e->getMessage(),
                    'historical_data' => []
                ];
            }
        }
        
        return view('ai.analysis', compact(
            'totalContributions', 
            'totalEnergy', 
            'totalBandwidth', 
            'totalWater', 
            'totalStorage',
            'predictions'
        ));
    }
    
    public function predictionHistory()
    {
        // Get contributions with predictions
        $contributions = ResourceContribution::with('user')
            ->whereNotNull('demand_prediction')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('ai.history', compact('contributions'));
    }
}