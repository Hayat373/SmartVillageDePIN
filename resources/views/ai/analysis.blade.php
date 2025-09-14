@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h2>AI Resource Analysis</h2>
        <p class="lead">Smart predictions for optimal resource allocation in your village</p>
        
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body text-center">
                        <h5 class="card-title">Total Contributions</h5>
                        <p class="card-text display-6">{{ $totalContributions }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body text-center">
                        <h5 class="card-title">Energy</h5>
                        <p class="card-text display-6">{{ $totalEnergy }} kWh</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-info mb-3">
                    <div class="card-body text-center">
                        <h5 class="card-title">Bandwidth</h5>
                        <p class="card-text display-6">{{ $totalBandwidth }} Mbps</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-body text-center">
                        <h5 class="card-title">Water</h5>
                        <p class="card-text display-6">{{ $totalWater }} L</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header bg-dark text-white">
                <h5>AI Demand Predictions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($predictions as $resource => $prediction)
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header 
                                @if($prediction['prediction'] == 'high') bg-danger 
                                @elseif($prediction['prediction'] == 'medium') bg-warning 
                                @elseif($prediction['prediction'] == 'low') bg-success 
                                @else bg-secondary @endif text-white">
                                <h6 class="mb-0">{{ ucfirst($resource) }} Demand: 
                                    <span class="text-uppercase">{{ $prediction['prediction'] }}</span>
                                </h6>
                            </div>
                            <div class="card-body">
                                <p>{{ $prediction['recommendation'] }}</p>
                                
                                @if(!empty($prediction['historical_data']))
                                <div class="mt-3">
                                    <h6>Historical Analysis:</h6>
                                    <ul class="list-unstyled">
                                        <li>30-day total: {{ number_format($prediction['historical_data']['total_30_days'], 2) }}</li>
                                        <li>Daily average: {{ number_format($prediction['historical_data']['average_daily'], 2) }}</li>
                                        <li>Trend: {{ number_format($prediction['historical_data']['trend'], 2) }}%</li>
                                    </ul>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header bg-info text-white">
                <h5>How Our AI Works</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5>1. Data Collection</h5>
                                <p>We analyze historical resource contributions from your village</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5>2. Trend Analysis</h5>
                                <p>Our AI identifies patterns and trends in resource usage</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5>3. Smart Prediction</h5>
                                <p>We predict future demand and provide allocation recommendations</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
