<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\ResourceContribution;
use App\Services\AIPredictionService;
use Illuminate\Support\Facades\Log;
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
        try {
            $validated = $request->validate([
                'resource_type' => 'required|in:energy,bandwidth,water,storage',
                'amount' => 'required|numeric|min:0.01',
                'hedera_tx_id' => 'required|string',
                'hedera_token_tx' => 'nullable|string'
            ]);

            $userId = auth()->id();
            $prediction = $this->aiService->predictDemand(
                $validated['resource_type'],
                $validated['amount'],
                $userId
            );

            $contribution = ResourceContribution::create([
                'user_id' => $userId,
                'resource_type' => $validated['resource_type'],
                'amount' => $validated['amount'],
                'transaction_id' => $validated['hedera_tx_id'],
                'hedera_token_tx' => $validated['hedera_token_tx'] ?? null,
                'demand_prediction' => $prediction['prediction']
            ]);

            Log::info("Contribution {$contribution->id} created", [
                'user_id' => $userId,
                'resource_type' => $validated['resource_type'],
                'amount' => $validated['amount'],
                'hedera_tx_id' => $validated['hedera_tx_id'],
                'hedera_token_tx' => $validated['hedera_token_tx'],
                'prediction' => $prediction
            ]);

            if ($prediction['prediction'] === 'high') {
                \Illuminate\Support\Facades\Mail::to(auth()->user()->email)->send(
                    new \App\Mail\HighDemandNotification($contribution)
                );
                Log::info("High demand notification sent for contribution {$contribution->id}");
            }

            return redirect()->route('dashboard')->with('success', 'Resource contributed successfully!');
        } catch (\Exception $e) {
            Log::error("ResourceController@store Error: {$e->getMessage()}", [
                'request' => $request->all(),
                'user_id' => auth()->id()
            ]);
            return redirect()->route('dashboard')->with('error', 'Failed to save contribution: ' . $e->getMessage());
        }
    }

    public function village()
    {
        try {
            $contributions = ResourceContribution::with('user')->orderBy('created_at', 'desc')->get();
            
            $totals = [
                'energy' => ResourceContribution::where('resource_type', 'energy')->sum('amount'),
                'bandwidth' => ResourceContribution::where('resource_type', 'bandwidth')->sum('amount'),
                'water' => ResourceContribution::where('resource_type', 'water')->sum('amount'),
                'storage' => ResourceContribution::where('resource_type', 'storage')->sum('amount')
            ];
            
            $predictions = $this->getVillagePredictions();
            
            return view('village', compact('contributions', 'totals', 'predictions'));
        } catch (\Exception $e) {
            Log::error("ResourceController@village Error: {$e->getMessage()}");
            return redirect()->route('dashboard')->with('error', 'Failed to load village data: ' . $e->getMessage());
        }
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
                Log::error("Prediction failed for {$type}: {$e->getMessage()}");
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