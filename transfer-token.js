// WARNING: Storing HEDERA_PRIVATE_KEY in .env is insecure for production. Use HashiCorp Vault.
const { Client, PrivateKey, TransferTransaction, TransactionId } = require('@hashgraph/sdk');
require('dotenv').config();

async function transferToken(toAccountId, amount, tokenId, operatorAccountId, operatorPrivateKey) {
    if (!toAccountId || !amount || !tokenId || !operatorAccountId || !operatorPrivateKey) {
        console.error('Missing arguments:', { toAccountId, amount, tokenId, operatorAccountId, operatorPrivateKey: operatorPrivateKey ? '****' : undefined });
        process.exit(1);
    }

    const client = Client.forTestnet();
    let privateKey;
    try {
        privateKey = PrivateKey.fromStringECDSA(operatorPrivateKey);
        console.log('Operator Public Key:', privateKey.publicKey.toString());
    } catch (error) {
        console.error('Operator ECDSA Key Error:', error.message);
        try {
            privateKey = PrivateKey.fromStringDer(operatorPrivateKey);
            console.log('Operator Public Key (DER):', privateKey.publicKey.toString());
        } catch (derError) {
            console.error('DER Key Error:', derError.message);
            process.exit(1);
        }
    }
    client.setOperator(operatorAccountId, privateKey);

    try {
        const transactionId = TransactionId.generate(operatorAccountId);
        const transferTransaction = await new TransferTransaction()
            .addTokenTransfer(tokenId, operatorAccountId, -amount)
            .addTokenTransfer(tokenId, toAccountId, amount)
            .setTransactionId(transactionId)
            .freezeWith(client)
            .sign(privateKey);
        const response = await transferTransaction.execute(client);
        const receipt = await response.getReceipt(client);
        console.log('Transfer Status:', receipt.status.toString());
        console.log('Transfer Tx ID:', transactionId.toString());
        console.log('Receipt:', {
            status: receipt.status.toString(),
            transactionId: receipt.transactionId ? receipt.transactionId.toString() : transactionId.toString()
        });
        process.stdout.write(transactionId.toString());
    } catch (error) {
        console.error('Transfer Error:', error.message, error);
        process.exit(1);
    }
}

const [,, toAccountId, amount, tokenId, operatorAccountId, operatorPrivateKey] = process.argv;
transferToken(toAccountId, parseInt(amount), tokenId, operatorAccountId, operatorPrivateKey);