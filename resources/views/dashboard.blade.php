@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8">
        <h2>Welcome, {{ auth()->user()->name }}!</h2>
        <p class="lead">Your SmartVillage DePIN Hub Dashboard</p>
        
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Contributions</h5>
                        <p class="card-text display-6">{{ $totalContributions }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-dark bg-light mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Hedera Account</h5>
                        <p class="card-text">{{ auth()->user()->hedera_account_id ?? 'Not set' }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header bg-primary text-white">
                <h5>Recent Contributions</h5>
            </div>
            <div class="card-body">
                @if($contributions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Resource</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th>Prediction</th>
                                    <th>Transaction ID</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($contributions as $contribution)
                                <tr>
                                    <td>{{ ucfirst($contribution->resource_type) }}</td>
                                    <td>{{ $contribution->amount }}</td>
                                    <td>{{ $contribution->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($contribution->demand_prediction == 'high') bg-danger 
                                            @elseif($contribution->demand_prediction == 'medium') bg-warning 
                                            @elseif($contribution->demand_prediction == 'low') bg-success 
                                            @else bg-secondary @endif">
                                            {{ ucfirst($contribution->demand_prediction) }}
                                        </span>
                                    </td>
                                    <td class="text-truncate" style="max-width: 150px;">{{ $contribution->transaction_id }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-center">You haven't made any contributions yet.</p>
                    <div class="text-center mt-3">
                        <a href="{{ route('contribute') }}" class="btn btn-success">Make Your First Contribution</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5>Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('contribute') }}" class="btn btn-success btn-lg">Contribute Resources</a>
                    <a href="{{ route('village') }}" class="btn btn-primary btn-lg">View Village Hub</a>
                    <a href="{{ route('ai.analysis') }}" class="btn btn-warning btn-lg">AI Analysis</a>
                </div>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header bg-warning text-dark">
                <h5>AI Insights</h5>
            </div>
            <div class="card-body">
                <p>Our AI analyzes community resources to help optimize allocation:</p>
                <ul>
                    <li>Monitors historical contribution patterns</li>
                    <li>Predicts future demand based on trends</li>
                    <li>Provides smart allocation recommendations</li>
                    <li>Adapts to seasonal changes</li>
                </ul>
                <div class="text-center mt-3">
                    <a href="{{ route('ai.analysis') }}" class="btn btn-sm btn-outline-warning">View Detailed Analysis</a>
                </div>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header bg-success text-white">
                <h5>Did You Know?</h5>
            </div>
            <div class="card-body">
                <p>Your contributions help our AI make better predictions for the entire community. The more data we have, the more accurate our demand forecasting becomes!</p>
            </div>
        </div>
    </div>
</div>
@endsection