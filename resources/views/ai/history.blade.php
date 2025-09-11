@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0">AI Prediction History</h2>
                <p class="text-muted mb-0">Historical record of all AI predictions for resource contributions</p>
            </div>
            <div>
                <a href="{{ route('ai.analysis') }}" class="btn btn-outline-primary">
                    <i class="fas fa-chart-line me-1"></i> Back to Analysis
                </a>
            </div>
        </div>
        
        <div class="card mt-4 border-0">
            <div class="card-header bg-primary text-white d-flex align-items-center">
                <i class="fas fa-history me-2"></i>
                <h5 class="mb-0">All Predictions</h5>
            </div>
            <div class="card-body">
                @if($contributions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-dark table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Contributor</th>
                                    <th>Resource</th>
                                    <th>Amount</th>
                                    <th>Prediction</th>
                                    <th>Recommendation</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($contributions as $contribution)
                                <tr>
                                    <td>{{ $contribution->created_at->format('M d, Y') }}</td>
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
                                    <td class="text-truncate" style="max-width: 300px;" data-bs-toggle="tooltip" title="{{ $contribution->allocation_recommendation }}">
                                        {{ $contribution->allocation_recommendation }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center mt-4">
                        {{ $contributions->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No predictions available yet.</p>
                        <a href="{{ route('contribute') }}" class="btn btn-primary mt-2">
                            <i class="fas fa-plus me-1"></i> Make Your First Contribution
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection