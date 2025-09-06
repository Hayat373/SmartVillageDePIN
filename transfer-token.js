const {
    Client,
    PrivateKey,
    AccountId,
    TransferTransaction,
    TokenId,
    TokenAssociateTransaction
} = require("@hashgraph/sdk");
require('dotenv').config();

async function transferToken(toAccountId, amount) {
    try {
        const client = Client.forTestnet();
        let privateKey;
        try {
            privateKey = PrivateKey.fromStringECDSA(process.env.HEDERA_PRIVATE_KEY);
        } catch (e) {
            console.log("ECDSA parsing failed, trying DER...");
            privateKey = PrivateKey.fromStringDer(process.env.HEDERA_PRIVATE_KEY);
        }
        client.setOperator(AccountId.fromString(process.env.HEDERA_ACCOUNT_ID), privateKey);

        const tokenId = TokenId.fromString(process.env.HEDERA_TOKEN_ID);

        // Associate token with recipient account
        const associateTx = await new TokenAssociateTransaction()
            .setAccountId(AccountId.fromString(toAccountId))
            .setTokenIds([tokenId])
            .execute(client);
        await associateTx.getReceipt(client);

        // Transfer tokens
        const transferTx = await new TransferTransaction()
            .addTokenTransfer(tokenId, process.env.HEDERA_ACCOUNT_ID, -amount)
            .addTokenTransfer(tokenId, toAccountId, amount)
            .execute(client);
        const receipt = await transferTx.getReceipt(client);

        console.log(`Transferred ${amount} VIL to ${toAccountId}. Tx ID: ${transferTx.transactionId}`);
    } catch (error) {
        console.error("Transfer failed:", error);
    }
}

if (process.argv.length !== 4) {
    console.error("Usage: node transfer-token.js <toAccountId> <amount>");
    process.exit(1);
}

transferToken(process.argv[2], parseInt(process.argv[3]));