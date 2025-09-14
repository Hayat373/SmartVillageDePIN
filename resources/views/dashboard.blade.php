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
                        @error('resource_type')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="number" name="amount" id="amount" step="0.01" min="0.01" class="form-control" required>
                        @error('amount')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <input type="hidden" name="hedera_tx_id" id="hedera_tx_id">
                    <input type="hidden" name="hedera_token_tx" id="hedera_token_tx">
                    <button type="submit" class="btn btn-success" id="submit-btn">Share Resource</button>
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
                                    <td class="text-truncate" style="max-width: 150px;">{{ $contribution->hedera_token_tx ?? 'None' }}</td>
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
            </div>
        </div>
        
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
                <p>Your contributions help our AI make better predictions for the community. More data, better forecasts!</p>
            </div>
        </div>
    </div>
    
    <script src="https://unpkg.com/@hashgraph/sdk@2.66.0/dist/browser/hedera.js"></script>
    <!-- For production: <script src="https://unpkg.com/@hashgraph/hashpack@0.3.0/dist/index.js"></script> -->
    <script>
        // WARNING: Exposing USER_PRIVATE_KEY in JavaScript is insecure for production. Use HashPack for client-side signing.
        const accountId = '{{ env('HEDERA_ACCOUNT_ID') }}';
        const privateKey = '{{ env('HEDERA_PRIVATE_KEY') }}';
        const topicId = '{{ env('HEDERA_TOPIC_ID') }}';
        const tokenId = '{{ env('HEDERA_TOKEN_ID') }}';
        const userAccountId = '{{ auth()->user()->hedera_account_id }}';
        const userPrivateKey = '{{ env('USER_PRIVATE_KEY') }}';

        const statusElement = document.getElementById('hedera-status');
        const submitBtn = document.getElementById('submit-btn');
        const form = document.getElementById('resource-form');

        if (!accountId || !privateKey || !topicId || !tokenId || !userAccountId || !userPrivateKey) {
            statusElement.innerText = 'Error: Missing Hedera configuration or user credentials';
            console.error('Missing variables:', {
                accountId,
                privateKey: privateKey ? '****' : undefined,
                topicId,
                tokenId,
                userAccountId,
                userPrivateKey: userPrivateKey ? '****' : undefined
            });
            submitBtn.disabled = true;
            return;
        }

        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            statusElement.innerText = 'Submitting to Hedera...';
            submitBtn.disabled = true;

            try {
                const client = Hedera.Client.forTestnet();
                let operatorPrivateKey;
                try {
                    operatorPrivateKey = Hedera.PrivateKey.fromStringECDSA(privateKey);
                    console.log('Operator Public Key:', operatorPrivateKey.publicKey.toString());
                } catch (error) {
                    console.error('Operator ECDSA Key Error:', error.message);
                    operatorPrivateKey = Hedera.PrivateKey.fromStringDer(privateKey);
                    console.log('Operator Public Key (DER):', operatorPrivateKey.publicKey.toString());
                }
                client.setOperator(accountId, operatorPrivateKey);

                let userPrivateKeyObj;
                try {
                    userPrivateKeyObj = Hedera.PrivateKey.fromStringECDSA(userPrivateKey);
                    console.log('User Public Key:', userPrivateKeyObj.publicKey.toString());
                } catch (error) {
                    console.error('User ECDSA Key Error:', error.message);
                    userPrivateKeyObj = Hedera.PrivateKey.fromStringDer(userPrivateKey);
                    console.log('User Public Key (DER):', userPrivateKeyObj.publicKey.toString());
                }

                const resourceType = document.getElementById('resource_type').value;
                const amount = parseFloat(document.getElementById('amount').value);
                console.log('Submitting contribution:', { resourceType, amount, userAccountId });

                // Log contribution to HCS
                let txId = 'unknown';
                try {
                    const topicTransaction = await new Hedera.TopicMessageSubmitTransaction({
                        topicId: Hedera.TopicId.fromString(topicId),
                        message: `Shared ${amount} of ${resourceType} by ${userAccountId}`
                    }).execute(client);
                    const topicReceipt = await topicTransaction.getReceipt(client);
                    txId = topicReceipt.topicSequenceNumber || topicTransaction.transactionId.toString();
                    statusElement.innerText = `Success! Logged to Hedera with ID: ${txId}`;
                    console.log('HCS Transaction:', { sequenceNumber: txId, status: topicReceipt.status.toString(), transactionId: topicTransaction.transactionId.toString() });
                    document.getElementById('hedera_tx_id').value = txId;
                } catch (hcsError) {
                    console.error('HCS Error:', hcsError.message, hcsError);
                    statusElement.innerText = `HCS Failed: ${hcsError.message}`;
                    throw hcsError;
                }

                // Associate user account with token
                let tokenTxId = '';
                try {
                    const assocTransactionId = Hedera.TransactionId.generate(accountId);
                    console.log('Generated Token Assoc Tx ID:', assocTransactionId.toString());
                    const assocTransaction = await new Hedera.TokenAssociateTransaction()
                        .setAccountId(userAccountId)
                        .setTokenIds([Hedera.TokenId.fromString(tokenId)])
                        .setTransactionId(assocTransactionId)
                        .freezeWith(client)
                        .sign(userPrivateKeyObj);
                    const assocResponse = await assocTransaction.execute(client);
                    const assocReceipt = await assocResponse.getReceipt(client);
                    console.log('Token Assoc Receipt:', { status: assocReceipt.status.toString(), transactionId: assocReceipt.transactionId?.toString() || assocTransactionId.toString() });
                    if (assocReceipt.status.toString() !== 'SUCCESS') {
                        throw new Error(`Token association failed: ${assocReceipt.status.toString()}`);
                    }
                } catch (assocError) {
                    if (assocError.message.includes('TOKEN_ALREADY_ASSOCIATED_TO_ACCOUNT')) {
                        console.log('Token already associated to user account');
                    } else {
                        console.error('Token Assoc Error:', assocError.message, assocError);
                        statusElement.innerText += `\nToken Association Failed: ${assocError.message}`;
                        throw assocError;
                    }
                }

                // Mint tokens
                let mintTransactionId;
                try {
                    mintTransactionId = Hedera.TransactionId.generate(accountId);
                    console.log('Generated Token Mint Tx ID:', mintTransactionId.toString());
                    const tokenTransaction = await new Hedera.TokenMintTransaction()
                        .setTokenId(Hedera.TokenId.fromString(tokenId))
                        .setAmount(100)
                        .setTransactionId(mintTransactionId)
                        .freezeWith(client)
                        .sign(operatorPrivateKey);
                    const tokenResponse = await tokenTransaction.execute(client);
                    const tokenReceipt = await tokenResponse.getReceipt(client);
                    console.log('Token Mint Receipt:', {
                        status: tokenReceipt.status.toString(),
                        totalSupply: tokenReceipt.totalSupply?.toString() || 'unknown',
                        transactionId: tokenReceipt.transactionId ? tokenReceipt.transactionId.toString() : mintTransactionId.toString()
                    });
                    if (tokenReceipt.status.toString() !== 'SUCCESS') {
                        throw new Error(`Token mint failed: ${tokenReceipt.status.toString()}`);
                    }
                    statusElement.innerText += `\nMinted 100 Village Tokens! Mint Tx: ${mintTransactionId.toString()}`;
                } catch (mintError) {
                    console.error('Token Mint Error:', mintError.message, mintError);
                    statusElement.innerText += `\nToken Mint Failed: ${mintError.message}`;
                    throw mintError;
                }

                // Transfer tokens to user
                try {
                    const transferTransactionId = Hedera.TransactionId.generate(accountId);
                    console.log('Generated Token Transfer Tx ID:', transferTransactionId.toString());
                    const transferTransaction = await new Hedera.TransferTransaction()
                        .addTokenTransfer(tokenId, accountId, -100)
                        .addTokenTransfer(tokenId, userAccountId, 100)
                        .setTransactionId(transferTransactionId)
                        .freezeWith(client)
                        .sign(operatorPrivateKey);
                    const transferResponse = await transferTransaction.execute(client);
                    const transferReceipt = await transferResponse.getReceipt(client);
                    console.log('Token Transfer Receipt:', {
                        status: transferReceipt.status.toString(),
                        transactionId: transferReceipt.transactionId?.toString() || transferTransactionId.toString()
                    });
                    if (transferReceipt.status.toString() === 'SUCCESS') {
                        statusElement.innerText += `\nTransferred 100 Village Tokens to user! Transfer Tx: ${transferTransactionId.toString()}`;
                        tokenTxId = transferTransactionId.toString();
                        document.getElementById('hedera_token_tx').value = tokenTxId;
                    } else {
                        throw new Error(`Token transfer failed: ${transferReceipt.status.toString()}`);
                    }
                } catch (transferError) {
                    console.error('Token Transfer Error:', transferError.message, transferError);
                    statusElement.innerText += `\nToken Transfer Failed: ${transferError.message}`;
                    throw transferError;
                }

                // Submit form
                const formData = new FormData(form);
                console.log('Form Data:', {
                    resource_type: formData.get('resource_type'),
                    amount: formData.get('amount'),
                    hedera_tx_id: formData.get('hedera_tx_id'),
                    hedera_token_tx: formData.get('hedera_token_tx')
                });

                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                if (response.ok) {
                    statusElement.innerText += '\nForm submitted successfully!';
                    window.location = '/dashboard';
                } else {
                    const errorText = await response.text();
                    console.error('Form Submission Error:', response.status, errorText);
                    throw new Error(`Form submission failed: ${response.status} - ${errorText}`);
                }
            } catch (error) {
                console.error('Hedera Error:', error.message, error);
                statusElement.innerText = `Error: ${error.message}`;
                const formData = new FormData(form);
                formData.set('hedera_tx_id', txId);
                formData.set('hedera_token_tx', '');
                console.log('Fallback Form Data:', {
                    resource_type: formData.get('resource_type'),
                    amount: formData.get('amount'),
                    hedera_tx_id: txId,
                    hedera_token_tx: ''
                });
                await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                window.location = '/dashboard';
            } finally {
                submitBtn.disabled = false;
            }
        });
    </script>
@endsection 
