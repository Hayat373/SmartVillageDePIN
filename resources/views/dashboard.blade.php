@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8">
        <h2>Welcome, {{ auth()->user()->name }}!</h2>
        <p class="lead">Your SmartVillage DePIN Hub Dashboard</p>
        
        <div class="card mt-4">
            <div class="card-header bg-primary text-white">
                <h5>Contribute a Resource</h5>
            </div>
            <div class="card-body">
                <form id="resource-form" method="POST" action="{{ route('contribute') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="resource_type" class="form-label">Resource Type</label>
                        <select name="resource_type" id="resource_type" class="form-select" required>
                            <option value="energy">Energy (kWh)</option>
                            <option value="bandwidth">Bandwidth (Mbps)</option>
                            <option value="water">Water (L)</option>
                            <option value="storage">Storage (GB)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="number" name="amount" id="amount" step="0.01" min="0.01" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-success">Share Resource</button>
                </form>
                <p id="hedera-status" class="mt-3"></p>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header bg-primary text-white">
                <h5>Recent Contributions</h5>
            </div>
            <div class="card-body">
                @if($contributions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Resource</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th>Prediction</th>
                                    <th>Transaction ID</th>
                                    <th>Reward Tx</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($contributions as $contribution)
                                <tr>
                                    <td>{{ ucfirst($contribution->resource_type) }}</td>
                                    <td>{{ $contribution->amount }}</td>
                                    <td>{{ $contribution->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($contribution->demand_prediction == 'high') bg-danger 
                                            @elseif($contribution->demand_prediction == 'medium') bg-warning 
                                            @elseif($contribution->demand_prediction == 'low') bg-success 
                                            @else bg-secondary @endif">
                                            {{ ucfirst($contribution->demand_prediction) }}
                                        </span>
                                    </td>
                                    <td class="text-truncate" style="max-width: 150px;">{{ $contribution->transaction_id }}</td>
                                    <td class="text-truncate" style="max-width: 150px;">{{ $contribution->hedera_token_tx }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-center">You haven't made any contributions yet.</p>
                    <div class="text-center mt-3">
                        <a href="{{ route('contribute') }}" class="btn btn-success">Make Your First Contribution</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5>Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('contribute') }}" class="btn btn-success btn-lg">Contribute Resources</a>
                    <a href="{{ route('village') }}" class="btn btn-primary btn-lg">View Village Hub</a>
                    <a href="{{ route('ai.analysis') }}" class="btn btn-warning btn-lg">AI Analysis</a>
                </div>
            </ personally>
            <div class="card mt-4">
                <div class="card-header bg-warning text-dark">
                    <h5>AI Insights</h5>
                </div>
                <div class="card-body">
                    <p>Our AI analyzes community resources to help optimize allocation:</p>
                    <ul>
                        <li>Monitors historical contribution patterns</li>
                        <li>Predicts future demand based on trends</li>
                        <li>Provides smart allocation recommendations</li>
                        <li>Adapts to seasonal changes</li>
                    </ul>
                    <div class="text-center mt-3">
                        <a href="{{ route('ai.analysis') }}" class="btn btn-sm btn-outline-warning">View Detailed Analysis</a>
                    </div>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-header bg-success text-white">
                    <h5>Did You Know?</h5>
                </div>
                <div class="card-body">
                    <p>Your contributions help our AI make better predictions for the entire community. The more data we have, the more accurate our demand forecasting becomes!</p>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://unpkg.com/@hashgraph/sdk@2.64.5/dist/browser/hedera.js"></script>
    <script>
        const accountId = '{{ env('HEDERA_ACCOUNT_ID') }}';
        const privateKey = '{{ env('HEDERA_PRIVATE_KEY') }}'; // WARNING: Insecure for production
        const topicId = '{{ env('HEDERA_TOPIC_ID') }}';
        const tokenId = '{{ env('HEDERA_TOKEN_ID') }}';

        document.getElementById('resource-form').addEventListener('submit', async (event) => {
            event.preventDefault();
            const statusElement = document.getElementById('hedera-status');
            statusElement.innerText = 'Submitting to Hedera...';

            const resourceType = document.getElementById('resource_type').value;
            const amount = document.getElementById('amount').value;

            try {
                const client = Hedera.Client.forTestnet();
                client.setOperator(accountId, privateKey);

                // Log contribution to HCS
                const transaction = await new Hedera.TopicMessageSubmitTransaction({
                    topicId: Hedera.TopicId.fromString(topicId),
                    message: `Shared ${amount} of ${resourceType}`
                }).execute(client);

                const receipt = await transaction.getReceipt(client);
                const txId = receipt.topicSequenceNumber;

                statusElement.innerText = `Success! Logged to Hedera with Sequence: ${txId}`;

                // Mint tokens as reward
                const tokenTransaction = await new Hedera.TokenMintTransaction()
                    .setTokenId(Hedera.TokenId.fromString(tokenId))
                    .setAmount(5) // Reward 5 tokens per contribution
                    .execute(client);
                const tokenReceipt = await tokenTransaction.getReceipt(client);
                statusElement.innerText += `\nRewarded 5 Village Tokens! Mint Tx: ${tokenReceipt.transactionId}`;

                // Submit form with transaction IDs
                const form = document.getElementById('resource-form');
                const formData = new FormData(form);
                formData.append('hedera_tx_id', txId.toString());
                formData.append('hedera_token_tx', tokenReceipt.transactionId.toString());
                await fetch(form.action, {
                    method: 'POST',
                    body: formData
                });
                window.location = '/dashboard';
            } catch (error) {
                console.error('Hedera Error:', error);
                statusElement.innerText = `Hedera Error: ${error.message}`;
                const form = document.getElementById('resource-form');
                const formData = new FormData(form);
                formData.append('hedera_tx_id', '');
                formData.append('hedera_token_tx', '');
                await fetch(form.action, {
                    method: 'POST',
                    body: formData
                });
                window.location = '/dashboard';
            }
        });
    </script>
@endsection