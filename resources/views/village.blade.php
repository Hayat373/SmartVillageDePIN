@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h2>SmartVillage Community Hub</h2>
        <p class="lead">View all resource contributions and community statistics</p>
        
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body text-center">
                        <h5 class="card-title">Total Energy</h5>
                        <p class="card-text display-6">{{ $totals['energy'] }} kWh</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-info mb-3">
                    <div class="card-body text-center">
                        <h5 class="card-title">Total Bandwidth</h5>
                        <p class="card-text display-6">{{ $totals['bandwidth'] }} Mbps</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body text-center">
                        <h5 class="card-title">Total Water</h5>
                        <p class="card-text display-6">{{ $totals['water'] }} L</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-body text-center">
                        <h5 class="card-title">Total Storage</h5>
                        <p class="card-text display-6">{{ $totals['storage'] }} GB</p>
                    </div>
                </div>
            </div>
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
                            <li>Energy: 60% immediate use, 40% storage</li>
                            <li>Bandwidth: Priority to education and healthcare</li>
                            <li>Water: 50% consumption, 30% agriculture, 20% reserve</li>
                            <li>Storage: Distributed across village nodes</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>Predicted Needs (Next 7 Days)</h6>
                        <ul>
                            <li>Energy demand will increase by 15%</li>
                            <li>Additional 20Mbps bandwidth needed during peak hours</li>
                            <li>Water reserves sufficient for 10 days</li>
                            <li>Storage allocation optimal for current usage</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection