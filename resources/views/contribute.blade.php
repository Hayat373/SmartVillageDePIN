@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">Contribute Resources to SmartVillage</h4>
            </div>
            <div class="card-body">
                <p class="lead">Share your resources with the community and earn recognition on the Hedera blockchain.</p>
                
                <form method="POST" action="{{ route('contribute') }}">
                    @csrf
                    
                    <div class="row mb-3">
                        <label for="resource_type" class="col-md-4 col-form-label text-md-end">Resource Type</label>
                        <div class="col-md-6">
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
                    
                    <div class="row mb-3">
                        <label for="amount" class="col-md-4 col-form-label text-md-end">Amount</label>
                        <div class="col-md-6">
                            <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{ old('amount') }}" required>
                            @error('amount')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-success btn-lg">Contribute via Hedera</button>
                        </div>
                    </div>
                </form>
                
                <div class="mt-5">
                    <h5>How It Works</h5>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h5>1</h5>
                                    <p>Select resource type and amount</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h5>2</h5>
                                    <p>Transaction recorded on Hedera</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h5>3</h5>
                                    <p>AI optimizes resource allocation</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h5>4</h5>
                                    <p>Community benefits from shared resources</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection