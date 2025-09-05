<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ResourceContribution;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $contributions = ResourceContribution::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        $totalContributions = ResourceContribution::where('user_id', $user->id)->count();
        
        return view('dashboard', compact('contributions', 'totalContributions'));
    }
}