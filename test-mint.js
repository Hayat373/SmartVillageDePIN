// test-mint.js
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
    const privateKey = PrivateKey.fromStringECDSA(process.env.HEDERA_PRIVATE_KEY);
    client.setOperator(process.env.HEDERA_ACCOUNT_ID, privateKey);

    try {
        const transactionId = TransactionId.generate(process.env.HEDERA_ACCOUNT_ID);
        const tx = await new TokenMintTransaction()
            .setTokenId(process.env.HEDERA_TOKEN_ID)
            .setAmount(5)
            .setTransactionId(transactionId)
            .execute(client);
        const receipt = await tx.getReceipt(client);
        console.log('Mint Status:', receipt.status.toString());
        console.log('Mint Tx ID:', transactionId.toString());
        console.log('Receipt:', {
            totalSupply: receipt.totalSupply.toString(),
            serials: receipt.serials.map(s => s.toString())
        });
    } catch (error) {
        console.error('Mint Error:', error.message, error);
    }
}

testMint();