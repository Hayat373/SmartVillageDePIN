// test-mint.js
// WARNING: Storing HEDERA_PRIVATE_KEY in .env is insecure for production. Use HashiCorp Vault.
const { Client, PrivateKey, TokenMintTransaction, TransactionId } = require('@hashgraph/sdk');
require('dotenv').config();

async function testMint() {
    if (!process.env.HEDERA_ACCOUNT_ID || !process.env.HEDERA_PRIVATE_KEY || !process.env.HEDERA_TOKEN_ID) {
        console.error('Missing .env variables:', {
            accountId: process.env.HEDERA_ACCOUNT_ID,
            privateKey: process.env.HEDERA_PRIVATE_KEY ? '****' : undefined,
            tokenId: process.env.HEDERA_TOKEN_ID
        });
        return;
    }

    const client = Client.forTestnet();
    let privateKey;
    try {
        privateKey = PrivateKey.fromStringECDSA(process.env.HEDERA_PRIVATE_KEY);
        console.log('Operator Public Key:', privateKey.publicKey.toString());
    } catch (error) {
        console.error('Operator ECDSA Key Error:', error.message);
        try {
            privateKey = PrivateKey.fromStringDer(process.env.HEDERA_PRIVATE_KEY);
            console.log('Operator Public Key (DER):', privateKey.publicKey.toString());
        } catch (derError) {
            console.error('DER Key Error:', derError.message);
            return;
        }
    }
    client.setOperator(process.env.HEDERA_ACCOUNT_ID, privateKey);

    try {
        const transactionId = TransactionId.generate(process.env.HEDERA_ACCOUNT_ID);
        const tx = await new TokenMintTransaction()
            .setTokenId(process.env.HEDERA_TOKEN_ID)
            .setAmount(100) // Mint 100 tokens
            .setTransactionId(transactionId)
            .execute(client);
        const receipt = await tx.getReceipt(client);
        console.log('Mint Status:', receipt.status.toString());
        console.log('Mint Tx ID:', transactionId.toString());
        console.log('Receipt:', {
            totalSupply: receipt.totalSupply.toString(),
            serials: receipt.serials.map(s => s.toString()),
            transactionId: receipt.transactionId ? receipt.transactionId.toString() : transactionId.toString()
        });
    } catch (error) {
        console.error('Mint Error:', error.message, error);
    }
}

testMint();