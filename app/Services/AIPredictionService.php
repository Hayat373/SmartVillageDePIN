<?php
namespace App\Services;
use App\Models\ResourceContribution;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResourceAlert;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Log;

class AIPredictionService
{
    public function predictDemand($resourceType, $amount, $userId = null)
    {
        try {
            // Historical data analysis
            $historicalData = ResourceContribution::where('resource_type', $resourceType)
                ->where('created_at', '>=', Carbon::now()->subDays(30))
                ->get();
            $totalContributed = $historicalData->sum('amount');
            $averageDaily = $totalContributed > 0 ? $totalContributed / 30 : 0;

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

            return [
                'prediction' => $prediction,
                'recommendation' => $recommendation,
                'historical_data' => [
                    'total_30_days' => $totalContributed,
                    'average_daily' => $averageDaily,
                    'trend' => $trend * 100
                ]
            ];
        } catch (\Exception $e) {
            Log::error("Prediction failed for {$resourceType}: {$e->getMessage()}", [
                'amount' => $amount,
                'userId' => $userId
            ]);
            return [
                'prediction' => 'unknown',
                'recommendation' => 'Error in prediction',
                'historical_data' => [
                    'total_30_days' => 0,
                    'average_daily' => 0,
                    'trend' => 0
                ]
            ];
        }
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
        try {
            $users = User::all();
            foreach ($users as $user) {
                Mail::to($user->email)->send(new ResourceAlert($resourceType, $recommendation));
                Log::info("Sent high demand notification to {$user->email} for {$resourceType}");
            }
        } catch (\Exception $e) {
            Log::error("Failed to send high demand notification for {$resourceType}: {$e->getMessage()}");
        }
    }

    public function rewardUser($userId, $amount)
    {
        try {
            $user = User::findOrFail($userId);
            if (!$user->hedera_account_id) {
                Log::error("User {$userId} has no Hedera account ID");
                return null;
            }
            $tokenId = env('HEDERA_TOKEN_ID');
            $operatorAccountId = env('HEDERA_ACCOUNT_ID');
            $operatorPrivateKey = env('HEDERA_PRIVATE_KEY');
            if (!$tokenId || !$operatorAccountId || !$operatorPrivateKey) {
                Log::error("Missing HEDERA_TOKEN_ID, HEDERA_ACCOUNT_ID, or HEDERA_PRIVATE_KEY in .env");
                return null;
            }

            // Call Node.js script for token transfer
            $command = "node transfer-token.js {$user->hedera_account_id} {$amount} {$tokenId} {$operatorAccountId} {$operatorPrivateKey}";
            $result = Process::run($command);
            if ($result->successful()) {
                $transactionId = trim($result->output());
                Log::info("Rewarded user {$userId} with {$amount} VIL tokens (Token ID: {$tokenId})", [
                    'transaction_id' => $transactionId
                ]);
                return $transactionId;
            } else {
                Log::error("Token transfer failed for user {$userId}: {$result->errorOutput()}");
                return null;
            }
        } catch (\Exception $e) {
            Log::error("Reward user {$userId} failed: {$e->getMessage()}");
            return null;
        }
    }
}