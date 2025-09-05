<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ResourceContribution;
use App\Services\AIPredictionService;

class ResourceController extends Controller
{
    protected $aiService;

    public function __construct(AIPredictionService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function create()
    {
        return view('contribute');
    }

    public function store(Request $request)
    {
        $request->validate([
            'resource_type' => 'required|in:energy,bandwidth,water,storage',
            'amount' => 'required|numeric|min:0.01'
        ]);

        try {
            // Get AI prediction
            $predictionResult = $this->aiService->predictDemand(
                $request->resource_type, 
                $request->amount
            );

            // Simulate Hedera transaction (for demo purposes)
            $transactionId = '0.0.' . rand(1000000, 9999999) . '.' . time();
            
            // Create resource contribution with AI prediction
            $contribution = ResourceContribution::create([
                'user_id' => auth()->id(),
                'resource_type' => $request->resource_type,
                'amount' => $request->amount,
                'transaction_id' => $transactionId,
                'demand_prediction' => $predictionResult['prediction'],
                'allocation_recommendation' => $predictionResult['recommendation']
            ]);
            
            return redirect()->route('dashboard')
                ->with('success', "Resource contributed successfully! AI prediction: " . 
                       ucfirst($predictionResult['prediction']) . " demand. " .
                       $predictionResult['recommendation']);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error processing your contribution: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function village()
    {
        // Get all contributions for the village view
        $contributions = ResourceContribution::with('user')
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Calculate totals by resource type
        $totals = [
            'energy' => ResourceContribution::where('resource_type', 'energy')->sum('amount'),
            'bandwidth' => ResourceContribution::where('resource_type', 'bandwidth')->sum('amount'),
            'water' => ResourceContribution::where('resource_type', 'water')->sum('amount'),
            'storage' => ResourceContribution::where('resource_type', 'storage')->sum('amount')
        ];
        
        // Get demand predictions summary
        $predictions = $this->getVillagePredictions();
        
        return view('village', compact('contributions', 'totals', 'predictions'));
    }
    
    /**
     * Get AI predictions for all resource types
     */
    private function getVillagePredictions()
    {
        $predictions = [];
        $resourceTypes = ['energy', 'bandwidth', 'water', 'storage'];
        
        foreach ($resourceTypes as $type) {
            // Get recent contributions for this resource type
            $recentAmount = ResourceContribution::where('resource_type', $type)
                ->where('created_at', '>=', now()->subDays(7))
                ->sum('amount');
                
            // If no recent data, use a default amount for prediction
            if ($recentAmount <= 0) {
                $recentAmount = 10; // Default value for demonstration
            }
            
            try {
                $predictionResult = $this->aiService->predictDemand($type, $recentAmount);
                $predictions[$type] = $predictionResult;
            } catch (\Exception $e) {
                $predictions[$type] = [
                    'prediction' => 'unknown',
                    'recommendation' => 'Error in prediction: ' . $e->getMessage(),
                    'historical_data' => []
                ];
            }
        }
        
        return $predictions;
    }
}