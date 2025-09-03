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
                                    <th>Transaction ID</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($contributions as $contribution)
                                <tr>
                                    <td>{{ ucfirst($contribution->resource_type) }}</td>
                                    <td>{{ $contribution->amount }}</td>
                                    <td>{{ $contribution->created_at->format('M d, Y') }}</td>
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
                </div>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header bg-warning text-dark">
                <h5>AI Insights</h5>
            </div>
            <div class="card-body">
                <p>Our AI predicts that your village will need:</p>
                <ul>
                    <li>15% more energy tomorrow due to cloudy weather</li>
                    <li>Additional bandwidth for educational content during school hours</li>
                    <li>Water storage optimization for the coming dry season</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection