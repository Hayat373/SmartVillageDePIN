<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ResourceContribution;

class DashboardController extends Controller
{
    public function index()
{
    $user = auth()->user();
    
    // Debug: Check if user has village_name
    if (empty($user->village_name)) {
        $user->village_name = 'Default Village';
        $user->save();
    }
    
    $contributions = ResourceContribution::where('user_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->get();
        
    $totalContributions = ResourceContribution::where('user_id', $user->id)->count();
    
    // Debug: Log the query results
    \Log::info('Dashboard data', [
        'user_id' => $user->id,
        'contributions_count' => $contributions->count(),
        'total_contributions' => $totalContributions
    ]);
    
    return view('dashboard', compact('contributions', 'totalContributions'));
}

}