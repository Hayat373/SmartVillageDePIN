<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContributionRequest;
use App\Models\ResourceContribution;
use App\Models\VillageStat;
use App\Services\HederaService;
use App\Services\AIPredictionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResourceController extends Controller
{
    protected $hederaService;
    protected $aiService;

    public function __construct(HederaService $hederaService, AIPredictionService $aiService)
    {
        $this->hederaService = $hederaService;
        $this->aiService = $aiService;
    }

    public function create()
    {
        $user = auth()->user();
        $recentContributions = ResourceContribution::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $villageStats = VillageStat::where('village_name', $user->village_name)
            ->orderBy('stat_date', 'desc')
            ->take(3)
            ->get();

        return view('contribute', compact('recentContributions', 'villageStats'));
    }

   public function store(StoreContributionRequest $request)
{
    DB::beginTransaction();

    try {
        $user = auth()->user();
        $validated = $request->validated();

        // Debug: Check if user has village_name
        if (empty($user->village_name)) {
            // Set default village name if not set
            $user->village_name = $validated['village_name'] ?? 'Default Village';
            $user->save();
        }

        // Create Hedera transaction (simulated)
        $transaction = $this->hederaService->createTransaction(
            $user->hedera_account_id,
            $validated['amount'],
            $validated['resource_type']
        );

        if (!$transaction['success']) {
            throw new \Exception('Hedera transaction failed: ' . $transaction['error']);
        }

        // Get AI prediction for this contribution
        $prediction = $this->aiService->predictResourceNeeds(
            $user->village_name, // Use user's village name instead of form input
            $validated['resource_type']
        );

        // Create resource contribution
        $contribution = ResourceContribution::create([
            'user_id' => $user->id,
            'resource_type' => $validated['resource_type'],
            'amount' => $validated['amount'],
            'transaction_id' => $transaction['transaction_id'],
            'status' => 'confirmed',
            'ai_analysis' => json_encode([
                'predicted_demand' => $prediction->predicted_demand,
                'predicted_supply' => $prediction->predicted_supply,
                'allocation_recommendation' => $prediction->allocation_recommendation,
                'confidence_score' => $prediction->accuracy_score
            ])
        ]);

        // Update village statistics
        $this->updateVillageStats($user->village_name, $validated['resource_type'], $validated['amount']);

        // Update user reputation
        $user->increment('reputation_score', 0.5);

        DB::commit();

        // Debug: Log the contribution
        \Log::info('Contribution created', [
            'user_id' => $user->id,
            'village' => $user->village_name,
            'contribution_id' => $contribution->id
        ]);

        return redirect()->route('dashboard')
            ->with('success', "Successfully contributed {$validated['amount']} {$validated['resource_type']}! " . 
                   "AI recommends: " . $this->generateAIMessage($prediction));

    } catch (\Exception $e) {
        DB::rollBack();
        
        // Log the error
        \Log::error('Contribution failed: ' . $e->getMessage());
        
        return redirect()->back()
            ->with('error', 'Contribution failed: ' . $e->getMessage())
            ->withInput();
    }
}


    protected function updateVillageStats($villageName, $resourceType, $amount)
    {
        $today = now()->format('Y-m-d');
        
        VillageStat::updateOrCreate(
            [
                'village_name' => $villageName,
                'resource_type' => $resourceType,
                'stat_date' => $today
            ],
            [
                'total_contributed' => DB::raw("total_contributed + $amount"),
                'contributor_count' => DB::raw('contributor_count + 1')
            ]
        );
    }

    protected function generateAIMessage($prediction)
    {
        $messages = [
            'energy' => [
                "Allocate " . ($prediction->allocation_recommendation * 100) . "% to storage for nighttime use",
                "Distribute " . ($prediction->allocation_recommendation * 100) . "% to neighboring villages",
                "Optimize grid distribution with " . ($prediction->allocation_recommendation * 100) . "% efficiency target"
            ],
            'bandwidth' => [
                "Prioritize educational content with " . ($prediction->allocation_recommendation * 100) . "% allocation",
                "Optimize mesh network routing for " . ($prediction->allocation_recommendation * 100) . "% better coverage",
                "Reserve " . ($prediction->allocation_recommendation * 100) . "% for emergency communications"
            ],
            'water' => [
                "Allocate " . ($prediction->allocation_recommendation * 100) . "% for agricultural use",
                "Purify and store " . ($prediction->allocation_recommendation * 100) . "% for future needs",
                "Distribute " . ($prediction->allocation_recommendation * 100) . "% to drought-affected areas"
            ],
            'storage' => [
                "Backup " . ($prediction->allocation_recommendation * 100) . "% for critical community data",
                "Allocate " . ($prediction->allocation_recommendation * 100) . "% for educational resources",
                "Optimize compression to save " . ($prediction->allocation_recommendation * 100) . "% space"
            ],
            'computing' => [
                "Allocate " . ($prediction->allocation_recommendation * 100) . "% for AI training tasks",
                "Reserve " . ($prediction->allocation_recommendation * 100) . "% for scientific research",
                "Distribute " . ($prediction->allocation_recommendation * 100) . "% to local startups"
            ]
        ];

        $resourceMessages = $messages[$prediction->resource_type] ?? $messages['energy'];
        return $resourceMessages[array_rand($resourceMessages)];
    }

   public function village()
{
    $user = auth()->user();
    
    // Ensure user has a village name
    if (empty($user->village_name)) {
        $user->village_name = 'Default Village';
        $user->save();
    }
    
    $villageName = $user->village_name;

    // Debug: Log the village query
    \Log::info('Village page accessed', [
        'user_id' => $user->id,
        'village_name' => $villageName
    ]);

    $contributions = ResourceContribution::with('user')
        ->whereHas('user', function($query) use ($villageName) {
            $query->where('village_name', $villageName);
        })
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    // Debug: Log contributions count
    \Log::info('Village contributions', [
        'village_name' => $villageName,
        'contributions_count' => $contributions->count()
    ]);

    // Get totals with a single query
    $totalsQuery = ResourceContribution::whereHas('user', function($query) use ($villageName) {
            $query->where('village_name', $villageName);
        })
        ->where('status', 'confirmed')
        ->select('resource_type', DB::raw('SUM(amount) as total'))
        ->groupBy('resource_type')
        ->get();

    // Convert to associative array
    $totals = [
        'energy' => 0,
        'bandwidth' => 0,
        'water' => 0,
        'storage' => 0,
        'computing' => 0,
    ];

    foreach ($totalsQuery as $total) {
        $totals[$total->resource_type] = $total->total;
    }

    $predictions = $this->aiService->getCurrentPredictions($villageName);

    $topContributors = ResourceContribution::with('user')
        ->whereHas('user', function($query) use ($villageName) {
            $query->where('village_name', $villageName);
        })
        ->select('user_id', DB::raw('SUM(amount) as total_contributed'))
        ->groupBy('user_id')
        ->orderBy('total_contributed', 'desc')
        ->take(5)
        ->get();

    return view('village', compact('contributions', 'totals', 'predictions', 'topContributors', 'villageName'));
}

}