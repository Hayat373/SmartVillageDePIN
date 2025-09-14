@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h2>AI Prediction History</h2>
        <p class="lead">Historical record of all AI predictions for resource contributions</p>
        
        <div class="card mt-4">
            <div class="card-header bg-primary text-white">
                <h5>All Predictions</h5>
            </div>
            <div class="card-body">
                @if($contributions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
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
                                    <td>{{ $contribution->user->name }}</td>
                                    <td>{{ ucfirst($contribution->resource_type) }}</td>
                                    <td>{{ $contribution->amount }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($contribution->demand_prediction == 'high') bg-danger 
                                            @elseif($contribution->demand_prediction == 'medium') bg-warning 
                                            @elseif($contribution->demand_prediction == 'low') bg-success 
                                            @else bg-secondary @endif">
                                            {{ ucfirst($contribution->demand_prediction) }}
                                        </span>
                                    </td>
                                    <td class="text-truncate" style="max-width: 300px;">{{ $contribution->allocation_recommendation }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center mt-4">
                        {{ $contributions->links() }}
                    </div>
                @else
                    <p class="text-center">No predictions available yet.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection