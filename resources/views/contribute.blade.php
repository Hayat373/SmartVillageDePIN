@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card border-0">
            <div class="card-header bg-success text-white d-flex align-items-center">
                <i class="fas fa-share-alt me-2"></i>
                <h4 class="mb-0">Contribute Resources to SmartVillage</h4>
            </div>
            <div class="card-body">
                <p class="lead text-muted">Share your resources with the community and earn recognition on the Hedera blockchain.</p>
                
                <div class="row">
                    <div class="col-md-7">
                        <form method="POST" action="{{ route('contribute') }}">
                            @csrf
                            
                            <div class="row mb-4">
                                <label for="resource_type" class="col-md-4 col-form-label text-md-end">Resource Type</label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <span class="input-group-text bg-dark border-dark">
                                            <i class="fas fa-cubes text-muted"></i>
                                        </span>
                                        <select class="form-select @error('resource_type') is-invalid @enderror" id="resource_type" name="resource_type" required>
                                            <option value="">Select Resource Type</option>
                                            <option value="energy" {{ old('resource_type') == 'energy' ? 'selected' : '' }}>Energy (kWh)</option>
                                            <option value="bandwidth" {{ old('resource_type') == 'bandwidth' ? 'selected' : '' }}>Bandwidth (Mbps)</option>
                                            <option value="water" {{ old('resource_type') == 'water' ? 'selected' : '' }}>Water (Liters)</option>
                                            <option value="storage" {{ old('resource_type') == 'storage' ? 'selected' : '' }}>Storage (GB)</option>
                                        </select>
                                        @error('resource_type')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row mb-4">
                                <label for="amount" class="col-md-4 col-form-label text-md-end">Amount</label>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <span class="input-group-text bg-dark border-dark">
                                            <i class="fas fa-hashtag text-muted"></i>
                                        </span>
                                        <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{ old('amount') }}" required>
                                        @error('amount')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-success btn-lg px-5 py-3">
                                        <i class="fas fa-link me-2"></i> Contribute via Hedera
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <div class="col-md-5">
                        <div class="card border-0 bg-dark">
                            <div class="card-body">
                                <h5 class="card-title mb-3">Current Community Needs</h5>
                                
                                <div class="resource-need mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="d-flex align-items-center">
                                            <i class="fas fa-bolt text-warning me-2"></i> Energy
                                        </span>
                                        <span class="badge bg-danger">High Need</span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 85%"></div>
                                    </div>
                                </div>
                                
                                <div class="resource-need mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="d-flex align-items-center">
                                            <i class="fas fa-wifi text-info me-2"></i> Bandwidth
                                        </span>
                                        <span class="badge bg-warning">Medium Need</span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: 60%"></div>
                                    </div>
                                </div>
                                
                                <div class="resource-need mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="d-flex align-items-center">
                                            <i class="fas fa-tint text-primary me-2"></i> Water
                                        </span>
                                        <span class="badge bg-success">Low Need</span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 30%"></div>
                                    </div>
                                </div>
                                
                                <div class="resource-need">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="d-flex align-items-center">
                                            <i class="fas fa-hdd text-secondary me-2"></i> Storage
                                        </span>
                                        <span class="badge bg-secondary">Stable</span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-secondary" role="progressbar" style="width: 45%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-5 pt-4 border-top border-secondary">
                    <h5 class="mb-4">How It Works</h5>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card text-center border-0 h-100">
                                <div class="card-body">
                                    <div class="step-number bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px;">
                                        <h5 class="mb-0">1</h5>
                                    </div>
                                    <h6>Select Resource</h6>
                                    <p class="small text-muted">Choose resource type and amount to contribute</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center border-0 h-100">
                                <div class="card-body">
                                    <div class="step-number bg-success rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px;">
                                        <h5 class="mb-0">2</h5>
                                    </div>
                                    <h6>Hedera Transaction</h6>
                                    <p class="small text-muted">Transaction recorded on Hedera blockchain</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center border-0 h-100">
                                <div class="card-body">
                                    <div class="step-number bg-warning rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px;">
                                        <h5 class="mb-0">3</h5>
                                    </div>
                                    <h6>AI Optimization</h6>
                                    <p class="small text-muted">AI optimizes resource allocation</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center border-0 h-100">
                                <div class="card-body">
                                    <div class="step-number bg-info rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px;">
                                        <h5 class="mb-0">4</h5>
                                    </div>
                                    <h6>Community Impact</h6>
                                    <p class="small text-muted">Community benefits from shared resources</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>  