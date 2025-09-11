@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0">AI Resource Analysis</h2>
                <p class="text-muted mb-0">Smart predictions for optimal resource allocation in your village</p>
            </div>
            <div class="d-flex">
                <a href="{{ route('ai.history') }}" class="btn btn-outline-primary me-2">
                    <i class="fas fa-history me-1"></i> View History
                </a>
                <button class="btn btn-primary">
                    <i class="fas fa-sync-alt me-1"></i> Refresh Data
                </button>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-md-3 mb-4">
                <div class="card h-100 glow-effect border-0">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-database fa-2x text-primary"></i>
                        </div>
                        <h5 class="card-title text-muted">Total Contributions</h5>
                        <p class="card-text display-6 fw-bold">{{ $totalContributions }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card h-100 glow-effect border-0">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-bolt fa-2x text-warning"></i>
                        </div>
                        <h5 class="card-title text-muted">Energy</h5>
                        <p class="card-text display-6 fw-bold">{{ $totalEnergy }} <small class="fs-6">kWh</small></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card h-100 glow-effect border-0">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-wifi fa-2x text-info"></i>
                        </div>
                        <h5 class="card-title text-muted">Bandwidth</h5>
                        <p class="card-text display-6 fw-bold">{{ $totalBandwidth }} <small class="fs-6">Mbps</small></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card h-100 glow-effect border-0">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-tint fa-2x text-primary"></i>
                        </div>
                        <h5 class="card-title text-muted">Water</h5>
                        <p class="card-text display-6 fw-bold">{{ $totalWater }} <small class="fs-6">L</small></p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mt-4 border-0">
            <div class="card-header bg-dark text-white d-flex align-items-center">
                <i class="fas fa-brain me-2"></i>
                <h5 class="mb-0">AI Demand Predictions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($predictions as $resource => $prediction)
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 border-0 glow-effect">
                            <div class="card-header d-flex align-items-center 
                                @if($prediction['prediction'] == 'high') bg-danger 
                                @elseif($prediction['prediction'] == 'medium') bg-warning 
                                @elseif($prediction['prediction'] == 'low') bg-success 
                                @else bg-secondary @endif text-white">
                                <i class="fas 
                                    @if($resource == 'energy') fa-bolt 
                                    @elseif($resource == 'bandwidth') fa-wifi 
                                    @elseif($resource == 'water') fa-tint 
                                    @elseif($resource == 'storage') fa-hdd 
                                    @else fa-cube @endif me-2"></i>
                                <h6 class="mb-0">{{ ucfirst($resource) }} Demand: 
                                    <span class="text-uppercase fw-bold">{{ $prediction['prediction'] }}</span>
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-3">
                                    <i class="fas fa-lightbulb text-warning mt-1 me-2"></i>
                                    <p class="mb-0">{{ $prediction['recommendation'] }}</p>
                                </div>
                                
                                @if(!empty($prediction['historical_data']))
                                <div class="mt-3">
                                    <h6 class="border-bottom pb-2">Historical Analysis</h6>
                                    <div class="row">
                                        <div class="col-4 text-center">
                                            <div class="border rounded p-2">
                                                <small class="text-muted d-block">30-day total</small>
                                                <span class="fw-bold">{{ number_format($prediction['historical_data']['total_30_days'], 2) }}</span>
                                            </div>
                                        </div>
                                        <div class="col-4 text-center">
                                            <div class="border rounded p-2">
                                                <small class="text-muted d-block">Daily average</small>
                                                <span class="fw-bold">{{ number_format($prediction['historical_data']['average_daily'], 2) }}</span>
                                            </div>
                                        </div>
                                        <div class="col-4 text-center">
                                            <div class="border rounded p-2">
                                                <small class="text-muted d-block">Trend</small>
                                                <span class="fw-bold {{ $prediction['historical_data']['trend'] > 0 ? 'text-success' : 'text-danger' }}">
                                                    {{ number_format($prediction['historical_data']['trend'], 2) }}%
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        
        <div class="card mt-4 border-0">
            <div class="card-header bg-info text-white d-flex align-items-center">
                <i class="fas fa-cogs me-2"></i>
                <h5 class="mb-0">How Our AI Works</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="card text-center border-0 h-100 glow-effect">
                            <div class="card-body p-4">
                                <div class="icon-container mb-3">
                                    <div class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                                        <i class="fas fa-database fa-2x text-white"></i>
                                    </div>
                                </div>
                                <h5>Data Collection</h5>
                                <p class="text-muted">We analyze historical resource contributions from your village</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card text-center border-0 h-100 glow-effect">
                            <div class="card-body p-4">
                                <div class="icon-container mb-3">
                                    <div class="rounded-circle bg-success d-inline-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                                        <i class="fas fa-chart-line fa-2x text-white"></i>
                                    </div>
                                </div>
                                <h5>Trend Analysis</h5>
                                <p class="text-muted">Our AI identifies patterns and trends in resource usage</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card text-center border-0 h-100 glow-effect">
                            <div class="card-body p-4">
                                <div class="icon-container mb-3">
                                    <div class="rounded-circle bg-warning d-inline-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                                        <i class="fas fa-robot fa-2x text-white"></i>
                                    </div>
                                </div>
                                <h5>Smart Prediction</h5>
                                <p class="text-muted">We predict future demand and provide allocation recommendations</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
