@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-lg">
                <div class="card-header bg-gradient-success text-white">
                    <h4 class="mb-0"><i class="fas fa-hand-holding-heart me-2"></i>Contribute Resources to Your Community</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Your contributions are recorded on the Hedera blockchain for transparency and verified using AI for optimal allocation.
                    </div>

                    <form method="POST" action="{{ route('contribute') }}" id="contributionForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="resource_type" class="form-label fw-bold">Resource Type *</label>
                                    <select class="form-select form-select-lg @error('resource_type') is-invalid @enderror" 
                                            id="resource_type" name="resource_type" required>
                                        <option value="">Select Resource Type</option>
                                        <option value="energy" {{ old('resource_type') == 'energy' ? 'selected' : '' }}>
                                            ⚡ Energy (kWh)
                                        </option>
                                        <option value="bandwidth" {{ old('resource_type') == 'bandwidth' ? 'selected' : '' }}>
                                            🌐 Bandwidth (Mbps)
                                        </option>
                                        <option value="water" {{ old('resource_type') == 'water' ? 'selected' : '' }}>
                                            💧 Water (Liters)
                                        </option>
                                        <option value="storage" {{ old('resource_type') == 'storage' ? 'selected' : '' }}>
                                            💾 Storage (GB)
                                        </option>
                                        <option value="computing" {{ old('resource_type') == 'computing' ? 'selected' : '' }}>
                                            🔢 Computing (GHz-hours)
                                        </option>
                                    </select>
                                    @error('resource_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="amount" class="form-label fw-bold">Amount *</label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" min="0.01" 
                                               class="form-control form-control-lg @error('amount') is-invalid @enderror" 
                                               id="amount" name="amount" value="{{ old('amount') }}" 
                                               placeholder="0.00" required>
                                        <span class="input-group-text" id="amountUnit">units</span>
                                    </div>
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="village_name" class="form-label fw-bold">Village/Community Name *</label>
                            <input type="text" class="form-control form-control-lg @error('village_name') is-invalid @enderror" 
                                   id="village_name" name="village_name" 
                                   value="{{ old('village_name', auth()->user()->village_name) }}" required>
                            @error('village_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Hedera Account</label>
                            <div class="alert alert-secondary">
                                <i class="fab fa-hedera me-2"></i>
                                <strong>{{ auth()->user()->hedera_account_id ?? 'Not configured' }}</strong>
                                <br>
                                <small class="text-muted">Transactions will be recorded on Hedera Testnet</small>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg py-3">
                                <i class="fas fa-paper-plane me-2"></i>
                                Submit Contribution via Hedera
                            </button>
                        </div>
                    </form>

                    <hr class="my-4">

                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="mb-3"><i class="fas fa-history me-2"></i>Your Recent Contributions</h5>
                            @if($recentContributions->count() > 0)
                                <div class="list-group">
                                    @foreach($recentContributions as $contribution)
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between">
                                            <span class="fw-bold text-capitalize">{{ $contribution->resource_type }}</span>
                                            <span class="badge bg-success rounded-pill">{{ $contribution->amount }}</span>
                                        </div>
                                        <small class="text-muted">{{ $contribution->created_at->diffForHumans() }}</small>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted">No contributions yet. Be the first in your community!</p>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <h5 class="mb-3"><i class="fas fa-chart-line me-2"></i>Village Statistics</h5>
                            @if($villageStats->count() > 0)
                                <div class="list-group">
                                    @foreach($villageStats as $stat)
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-capitalize fw-bold">{{ $stat->resource_type }}</span>
                                            <span class="badge bg-info">{{ number_format($stat->total_contributed) }}</span>
                                        </div>
                                        <small class="text-muted">{{ $stat->stat_date->format('M j, Y') }} • {{ $stat->contributor_count }} contributors</small>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted">No statistics available yet for your village.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card text-center border-0 shadow-sm">
                        <div class="card-body">
                            <i class="fas fa-link fa-2x text-success mb-3"></i>
                            <h5>Hedera Blockchain</h5>
                            <p class="text-muted">All contributions are immutably recorded on decentralized ledger</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center border-0 shadow-sm">
                        <div class="card-body">
                            <i class="fas fa-brain fa-2x text-primary mb-3"></i>
                            <h5>AI Optimization</h5>
                            <p class="text-muted">Smart algorithms predict needs and optimize resource allocation</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center border-0 shadow-sm">
                        <div class="card-body">
                            <i class="fas fa-users fa-2x text-warning mb-3"></i>
                            <h5>Community Impact</h5>
                            <p class="text-muted">See real-time impact of your contributions on village development</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const resourceTypeSelect = document.getElementById('resource_type');
    const amountUnit = document.getElementById('amountUnit');
    
    // Update unit display based on resource type
    const units = {
        'energy': 'kWh',
        'bandwidth': 'Mbps',
        'water': 'Liters',
        'storage': 'GB',
        'computing': 'GHz-h'
    };
    
    resourceTypeSelect.addEventListener('change', function() {
        const unit = units[this.value] || 'units';
        amountUnit.textContent = unit;
    });
    
    // Trigger change event initially
    if (resourceTypeSelect.value) {
        resourceTypeSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endpush