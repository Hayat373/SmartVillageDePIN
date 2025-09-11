@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0">SmartVillage Community Hub</h2>
                <p class="text-muted mb-0">View all resource contributions and community statistics</p>
            </div>
            <div>
                <a href="{{ route('ai.analysis') }}" class="btn btn-outline-primary">
                    <i class="fas fa-brain me-1"></i> AI Analysis
                </a>
            </div>
        </div>
        
        <div class="row mt-4">
            @foreach (['energy' => 'kWh', 'bandwidth' => 'Mbps', 'water' => 'L', 'storage' => 'GB'] as $type => $unit)
                <div class="col-md-3 mb-4">
                    <div class="card h-100 border-0 glow-effect">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <i class="fas 
                                    @if($type == 'energy') fa-bolt text-warning 
                                    @elseif($type == 'bandwidth') fa-wifi text-info 
                                    @elseif($type == 'water') fa-tint text-primary 
                                    @elseif($type == 'storage') fa-hdd text-secondary 
                                    @endif fa-2x"></i>
                            </div>
                            <h5 class="card-title text-muted">Total {{ ucfirst($type) }}</h5>
                            <p class="card-text display-6 fw-bold">{{ $totals[$type] }} <small class="fs-6">{{ $unit }}</small></p>
                            <div class="mt-3">
                                <span class="badge 
                                    @if($predictions[$type]['prediction'] == 'high') bg-danger 
                                    @elseif($predictions[$type]['prediction'] == 'medium') bg-warning 
                                    @elseif($predictions[$type]['prediction'] == 'low') bg-success 
                                    @else bg-secondary @endif mb-2">
                                    {{ ucfirst($predictions[$type]['prediction']) }} demand
                                </span>
                                <p class="small text-muted mb-0">{{ $predictions[$type]['recommendation'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="card mt-4 border-0">
            <div class="card-header bg-dark text-white d-flex align-items-center">
                <i class="fas fa-users me-2"></i>
                <h5 class="mb-0">All Community Contributions</h5>
            </div>
            <div class="card-body">
                @if($contributions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-dark table-hover">
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
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-secondary rounded-circle d-flex align-items-center justify-content-center me-2">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            {{ $contribution->user->name }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas 
                                                @if($contribution->resource_type == 'energy') fa-bolt text-warning 
                                                @elseif($contribution->resource_type == 'bandwidth') fa-wifi text-info 
                                                @elseif($contribution->resource_type == 'water') fa-tint text-primary 
                                                @elseif($contribution->resource_type == 'storage') fa-hdd text-secondary 
                                                @endif me-2"></i>
                                            {{ ucfirst($contribution->resource_type) }}
                                        </div>
                                    </td>
                                    <td>{{ $contribution->amount }}</td>
                                    <td>{{ $contribution->created_at->format('M d, Y') }}</td>
                                    <td class="text-truncate" style="max-width: 150px;">{{ $contribution->transaction_id }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($contribution->demand_prediction == 'high') bg-danger 
                                            @elseif($contribution->demand_prediction == 'medium') bg-warning 
                                            @elseif($contribution->demand_prediction == 'low') bg-success 
                                            @else bg-secondary @endif">
                                            <i class="fas 
                                                @if($contribution->demand_prediction == 'high') fa-arrow-up 
                                                @elseif($contribution->demand_prediction == 'medium') fa-equals 
                                                @elseif($contribution->demand_prediction == 'low') fa-arrow-down 
                                                @endif me-1"></i>
                                            {{ ucfirst($contribution->demand_prediction) }}
                                        </span>
                                    </td>
                                    <td class="text-truncate" style="max-width: 150px;">
                                        @if($contribution->hedera_token_tx)
                                            <span class="badge bg-success">Rewarded</span>
                                        @else
                                            <span class="badge bg-secondary">None</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No contributions have been made yet.</p>
                        <a href="{{ route('contribute') }}" class="btn btn-primary mt-2">
                            <i class="fas fa-plus me-1"></i> Make Your First Contribution
                        </a>
                    </div>
                @endif
            </div>
        </div>
        
        <div class="card mt-4 border-0">
            <div class="card-header bg-info text-white d-flex align-items-center">
                <i class="fas fa-robot me-2"></i>
                <h5 class="mb-0">AI-Powered Resource Allocation</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="border-bottom pb-2 mb-3">Current Allocation Strategy</h6>
                        <ul class="list-unstyled">
                            @foreach ($predictions as $type => $data)
                                <li class="mb-3">
                                    <div class="d-flex align-items-start">
                                        <i class="fas 
                                            @if($type == 'energy') fa-bolt text-warning 
                                            @elseif($type == 'bandwidth') fa-wifi text-info 
                                            @elseif($type == 'water') fa-tint text-primary 
                                            @elseif($type == 'storage') fa-hdd text-secondary 
                                            @endif mt-1 me-2"></i>
                                        <div>
                                            <strong>{{ ucfirst($type) }}:</strong> {{ $data['recommendation'] }}
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="border-bottom pb-2 mb-3">Predicted Needs (Next 7 Days)</h6>
                        <ul class="list-unstyled">
                            @foreach ($predictions as $type => $data)
                                <li class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <i class="fas 
                                                @if($type == 'energy') fa-bolt text-warning 
                                                @elseif($type == 'bandwidth') fa-wifi text-info 
                                                @elseif($type == 'water') fa-tint text-primary 
                                                @elseif($type == 'storage') fa-hdd text-secondary 
                                                @endif me-2"></i>
                                            <span>{{ ucfirst($type) }}:</span>
                                        </div>
                                        <div>
                                            <span class="badge 
                                                @if($data['prediction'] == 'high') bg-danger 
                                                @elseif($data['prediction'] == 'medium') bg-warning 
                                                @elseif($data['prediction'] == 'low') bg-success 
                                                @else bg-secondary @endif me-2">
                                                {{ ucfirst($data['prediction']) }} demand
                                            </span>
                                            <span class="badge bg-dark">
                                                {{ number_format($data['historical_data']['trend'], 2) }}% trend
                                            </span>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection