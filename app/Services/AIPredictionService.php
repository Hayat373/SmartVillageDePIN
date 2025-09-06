<?php
namespace App\Services;
use App\Models\ResourceContribution;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResourceAlert;
use Illuminate\Support\Facades\Process;

class AIPredictionService
{
    public function predictDemand($resourceType, $amount, $userId)
    {
        // Historical data analysis
        $historicalData = ResourceContribution::where('resource_type', $resourceType)
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->get();
        $totalContributed = $historicalData->sum('amount');
        $averageDaily = $totalContributed / 30;

        $recentWeek = ResourceContribution::where('resource_type', $resourceType)
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->sum('amount');
        $previousWeek = ResourceContribution::where('resource_type', $resourceType)
            ->whereBetween('created_at', [Carbon::now()->subDays(14), Carbon::now()->subDays(7)])
            ->sum('amount');
        $trend = $previousWeek > 0 ? ($recentWeek - $previousWeek) / $previousWeek : 0;

        $seasonFactor = $this->getSeasonalFactor($resourceType);
        $predictedNeed = $averageDaily * (1 + $trend) * $seasonFactor;
        $ratio = $amount / max($predictedNeed, 0.1);
        $prediction = $ratio < 0.7 ? 'high' : ($ratio < 1.3 ? 'medium' : 'low');

        $recommendation = $this->generateRecommendation($resourceType, $prediction, $amount);

        // Agentic Action: Notify on high demand
        if ($prediction === 'high') {
            $this->notifyHighDemand($resourceType, $recommendation);
        }

        // Agentic Action: Reward user if contribution exceeds predicted need
        if ($amount > $predictedNeed) {
            $this->rewardUser($userId, 100); // Reward 100 VIL tokens
        }

        return [
            'prediction' => $prediction,
            'recommendation' => $recommendation,
            'historical_data' => [
                'total_30_days' => $totalContributed,
                'average_daily' => $averageDaily,
                'trend' => $trend * 100
            ]
        ];
    }

    private function getSeasonalFactor($resourceType)
    {
        $month = Carbon::now()->month;
        $factors = [
            'energy' => in_array($month, [12, 1, 2]) ? 1.2 : 0.9,
            'bandwidth' => 1.0,
            'water' => in_array($month, [6, 7, 8]) ? 1.3 : 0.8,
            'storage' => 1.0
        ];
        return $factors[$resourceType] ?? 1.0;
    }

    private function generateRecommendation($resourceType, $prediction, $amount)
    {
        $recommendations = [
            'energy' => [
                'high' => "Demand high. Allocate $amount kWh to essentials. Consider solar expansion.",
                'medium' => "Demand medium. Distribute $amount kWh evenly.",
                'low' => "Demand low. Store $amount kWh for future."
            ],
            'bandwidth' => [
                'high' => "Demand high. Prioritize $amount Mbps for critical services.",
                'medium' => "Demand medium. Allocate $amount Mbps evenly.",
                'low' => "Demand low. Reserve $amount Mbps for peak times."
            ],
            'water' => [
                'high' => "Demand high. Allocate $amount liters for drinking and sanitation.",
                'medium' => "Demand medium. Distribute $amount liters across needs.",
                'low' => "Demand low. Store $amount liters in reserve."
            ],
            'storage' => [
                'high' => "Demand high. Prioritize $amount GB for critical data.",
                'medium' => "Demand medium. Allocate $amount GB across nodes.",
                'low' => "Demand low. Use $amount GB for archives."
            ]
        ];
        return $recommendations[$resourceType][$prediction] ?? "Allocate based on needs.";
    }

    private function notifyHighDemand($resourceType, $recommendation)
    {
        $users = User::all();
        foreach ($users as $user) {
            Mail::to($user->email)->send(new ResourceAlert($resourceType, $recommendation));
        }
    }

   private function rewardUser($userId, $amount)
{
    $user = User::findOrFail($userId);
    if (!$user->hedera_account_id) {
        \Log::error("User {$userId} has no Hedera account ID");
        return;
    }
    $tokenId = env('HEDERA_TOKEN_ID');
    if (!$tokenId) {
        \Log::error("HEDERA_TOKEN_ID not set in .env");
        return;
    }
    // Call Node.js script for token transfer
    $result = Process::run("node transfer-token.js {$user->hedera_account_id} {$amount}");
    if ($result->successful()) {
        \Log::info("Rewarded user {$userId} with {$amount} VIL tokens (Token ID: {$tokenId})");
    } else {
        \Log::error("Token transfer failed: {$result->errorOutput()}");
    }
}
}