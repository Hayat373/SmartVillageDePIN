// associate-token.js
const { Client, PrivateKey, TokenAssociateTransaction } = require('@hashgraph/sdk');
require('dotenv').config();

async function associateToken() {
    if (!process.env.HEDERA_ACCOUNT_ID || !process.env.HEDERA_PRIVATE_KEY || !process.env.HEDERA_TOKEN_ID || !process.env.USER_PRIVATE_KEY) {
        console.error('Missing .env variables:', {
            accountId: process.env.HEDERA_ACCOUNT_ID,
            operatorPrivateKey: process.env.HEDERA_PRIVATE_KEY ? '****' : undefined,
            tokenId: process.env.HEDERA_TOKEN_ID,
            userPrivateKey: process.env.USER_PRIVATE_KEY ? '****' : undefined
        });
        return;
    }

    const client = Client.forTestnet();
    let operatorPrivateKey;
    try {
        operatorPrivateKey = PrivateKey.fromStringECDSA(process.env.HEDERA_PRIVATE_KEY);
        console.log('Operator Public Key:', operatorPrivateKey.publicKey.toString());
    } catch (error) {
        console.error('Operator ECDSA Key Error:', error.message);
        operatorPrivateKey = PrivateKey.fromStringDer(process.env.HEDERA_PRIVATE_KEY);
        console.log('Operator Public Key (DER):', operatorPrivateKey.publicKey.toString());
    }
    client.setOperator(process.env.HEDERA_ACCOUNT_ID, operatorPrivateKey);

    let userPrivateKey;
    try {
        userPrivateKey = PrivateKey.fromStringECDSA(process.env.USER_PRIVATE_KEY);
        console.log('User Public Key:', userPrivateKey.publicKey.toString());
    } catch (error) {
        console.error('User ECDSA Key Error:', error.message);
        userPrivateKey = PrivateKey.fromStringDer(process.env.USER_PRIVATE_KEY);
        console.log('User Public Key (DER):', userPrivateKey.publicKey.toString());
    }

    try {
        const transaction = await new TokenAssociateTransaction()
            .setAccountId('0.0.6780733') // New user account ID
            .setTokenIds([process.env.HEDERA_TOKEN_ID])
            .freezeWith(client)
            .sign(userPrivateKey); // Sign with user’s key
        const txResponse = await transaction.execute(client);
        const receipt = await txResponse.getReceipt(client);
        console.log('Association status:', receipt.status.toString());
        console.log('Transaction ID:', txResponse.transactionId.toString());
    } catch (error) {
        console.error('Association Error:', error.message, error);
    }
}

associateToken();