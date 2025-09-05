<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ResourceContribution;
use Illuminate\Support\Facades\Http;

class ResourceController extends Controller
{
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

        // Simulate Hedera transaction (for demo purposes)
        $transactionId = '0.0.' . rand(1000000, 9999999) . '.' . time();
        
        // Create resource contribution
        $contribution = ResourceContribution::create([
            'user_id' => auth()->id(),
            'resource_type' => $request->resource_type,
            'amount' => $request->amount,
            'transaction_id' => $transactionId
        ]);
        
        // AI Prediction (simplified for demo)
        $prediction = $this->predictResourceNeed($request->resource_type, $request->amount);
        
        return redirect()->route('dashboard')
            ->with('success', "Resource contributed successfully! AI suggests: $prediction");
    }
    
    private function predictResourceNeed($type, $amount)
    {
        // Simple AI prediction logic (this would be more complex in a real app)
        $predictions = [
            'energy' => ["Increase solar panel efficiency by 15%", "Distribute 30% to neighboring village", "Store excess for night use"],
            'bandwidth' => ["Optimize mesh network routing", "Prioritize educational content", "Share 40% with school during day"],
            'water' => ["Purify and distribute to storage tanks", "Allocate 25% for farming", "Save excess for dry season"],
            'storage' => ["Backup critical community data", "Allocate 50% for educational resources", "Optimize compression algorithms"]
        ];
        
        return $predictions[$type][array_rand($predictions[$type])];
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
        
        return view('village', compact('contributions', 'totals'));
    }
}