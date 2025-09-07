@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h2>SmartVillage Community Hub</h2>
        <p class="lead">View all resource contributions and community statistics</p>
        
        <div class="row mt-4">
            @foreach (['energy' => 'kWh', 'bandwidth' => 'Mbps', 'water' => 'L', 'storage' => 'GB'] as $type => $unit)
                <div class="col-md-3">
                    <div class="card text-white {{ $type == 'energy' ? 'bg-success' : ($type == 'bandwidth' ? 'bg-info' : ($type == 'water' ? 'bg-primary' : 'bg-warning')) }} mb-3">
                        <div class="card-body text-center">
                            <h5 class="card-title">Total {{ ucfirst($type) }}</h5>
                            <p class="card-text display-6">{{ $totals[$type] }} {{ $unit }}</p>
                            <p>Prediction: {{ ucfirst($predictions[$type]['prediction']) }}</p>
                            <p>{{ $predictions[$type]['recommendation'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="card mt-4">
            <div class="card-header bg-dark text-white">
                <h5>All Community Contributions</h5>
            </div>
            <div class="card-body">
                @if($contributions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Contributor</th>
                                    <th>Resource</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th>Transaction ID</th>
                                    <th>Prediction</th>
                                    <th>Reward Tx</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($contributions as $contribution)
                                <tr>
                                    <td>{{ $contribution->user->name }}</td>
                                    <td>{{ ucfirst($contribution->resource_type) }}</td>
                                    <td>{{ $contribution->amount }}</td>
                                    <td>{{ $contribution->created_at->format('M d, Y') }}</td>
                                    <td class="text-truncate" style="max-width: 150px;">{{ $contribution->transaction_id }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($contribution->demand_prediction == 'high') bg-danger 
                                            @elseif($contribution->demand_prediction == 'medium') bg-warning 
                                            @elseif($contribution->demand_prediction == 'low') bg-success 
                                            @else bg-secondary @endif">
                                            {{ ucfirst($contribution->demand_prediction) }}
                                        </span>
                                    </td>
                                    <td class="text-truncate" style="max-width: 150px;">{{ $contribution->hedera_token_tx ?? 'None' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-center">No contributions have been made yet.</p>
                @endif
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header bg-info text-white">
                <h5>AI-Powered Resource Allocation</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Current Allocation Strategy</h6>
                        <ul>
                            @foreach ($predictions as $type => $data)
                                <li>{{ ucfirst($type) }}: {{ $data['recommendation'] }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>Predicted Needs (Next 7 Days)</h6>
                        <ul>
                            @foreach ($predictions as $type => $data)
                                <li>{{ ucfirst($type) }}: {{ ucfirst($data['prediction']) }} demand ({{ number_format($data['historical_data']['trend'], 2) }}% trend)</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection