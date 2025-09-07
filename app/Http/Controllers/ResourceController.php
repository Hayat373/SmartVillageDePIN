<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\ResourceContribution;
use App\Services\AIPredictionService;
use Carbon\Carbon;

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
            'amount' => 'required|numeric|min:0.01',
            'hedera_tx_id' => 'nullable|string',
            'hedera_token_tx' => 'nullable|string'
        ]);

        try {
            $contribution = ResourceContribution::create([
                'user_id' => auth()->id(),
                'resource_type' => $request->resource_type,
                'amount' => $request->amount,
                'transaction_id' => $request->hedera_tx_id ?? '0.0.' . rand(1000000, 9999999) . '.' . time(),
                'hedera_token_tx' => $request->hedera_token_tx
            ]);

            $predictionResult = $this->aiService->predictDemand(
                $request->resource_type,
                $request->amount,
                auth()->id()
            );

            \Log::info("Contribution {$contribution->id} created with hedera_token_tx: {$request->hedera_token_tx}");
            \Log::info("Prediction for contribution {$contribution->id}: ", $predictionResult);

            $contribution->update([
                'demand_prediction' => $predictionResult['prediction'],
                'allocation_recommendation' => $predictionResult['recommendation']
            ]);

            return redirect()->route('dashboard')
                ->with('success', "Resource contributed successfully! AI prediction: " .
                       ucfirst($predictionResult['prediction']) . " demand. " .
                       $predictionResult['recommendation']);
        } catch (\Exception $e) {
            \Log::error("Contribution failed: {$e->getMessage()}");
            return redirect()->route('contribute')
                ->with('error', "Failed to save contribution: {$e->getMessage()}");
        }
    }

    public function village()
    {
        $contributions = ResourceContribution::with('user')
            ->orderBy('created_at', 'desc')
            ->get();
            
        $totals = [
            'energy' => ResourceContribution::where('resource_type', 'energy')->sum('amount'),
            'bandwidth' => ResourceContribution::where('resource_type', 'bandwidth')->sum('amount'),
            'water' => ResourceContribution::where('resource_type', 'water')->sum('amount'),
            'storage' => ResourceContribution::where('resource_type', 'storage')->sum('amount')
        ];
        
        $predictions = $this->getVillagePredictions();
        
        return view('village', compact('contributions', 'totals', 'predictions'));
    }

    private function getVillagePredictions()
    {
        $predictions = [];
        $resourceTypes = ['energy', 'bandwidth', 'water', 'storage'];
        
        foreach ($resourceTypes as $type) {
            $recentAmount = ResourceContribution::where('resource_type', $type)
                ->where('created_at', '>=', Carbon::now()->subDays(7))
                ->sum('amount');
                
            if ($recentAmount <= 0) {
                $recentAmount = 10;
            }
            
            try {
                $predictions[$type] = $this->aiService->predictDemand($type, $recentAmount);
            } catch (\Exception $e) {
                \Log::error("Prediction failed for {$type}: {$e->getMessage()}");
                $predictions[$type] = [
                    'prediction' => 'unknown',
                    'recommendation' => 'Error in prediction: ' . $e->getMessage(),
                    'historical_data' => [
                        'total_30_days' => 0,
                        'average_daily' => 0,
                        'trend' => 0
                    ]
                ];
            }
        }
        
        return $predictions;
    }
}