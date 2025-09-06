const {
    Client,
    PrivateKey,
    AccountId,
    TokenCreateTransaction,
    TokenType,
    TokenSupplyType,
    Hbar,
    TopicMessageSubmitTransaction
} = require("@hashgraph/sdk");
require('dotenv').config();

async function createToken() {
    try {
        // Initialize Hedera client
        const accountId = AccountId.fromString(process.env.HEDERA_ACCOUNT_ID);
        let privateKey;
        try {
            // Try ECDSA first
            privateKey = PrivateKey.fromStringECDSA(process.env.HEDERA_PRIVATE_KEY);
        } catch (e) {
            // Fallback to DER-encoded key
            console.log("ECDSA parsing failed, trying DER...");
            privateKey = PrivateKey.fromStringDer(process.env.HEDERA_PRIVATE_KEY);
        }
        const client = Client.forTestnet();
        client.setOperator(accountId, privateKey);

        // Create Village Token
        const transaction = new TokenCreateTransaction()
            .setTokenName("Village Token")
            .setTokenSymbol("VIL")
            .setTokenType(TokenType.FungibleCommon)
            .setDecimals(0)
            .setInitialSupply(1000000)
            .setTreasuryAccountId(accountId)
            .setAdminKey(privateKey.publicKey)
            .setSupplyKey(privateKey.publicKey)
            .setFreezeDefault(false)
            .setMaxTransactionFee(new Hbar(30));

        // Execute transaction
        const txResponse = await transaction.execute(client);
        const receipt = await txResponse.getReceipt(client);
        const tokenId = receipt.tokenId;

        // Log to HCS Topic
        const topicId = process.env.HEDERA_TOPIC_ID;
        if (topicId) {
            await new TopicMessageSubmitTransaction()
                .setTopicId(topicId)
                .setMessage(`Created Village Token with Token ID: ${tokenId.toString()}`)
                .execute(client);
            console.log(`Logged to Topic ID ${topicId}`);
        }

        console.log(`Token ID: ${tokenId.toString()}`);
        console.log(`Transaction ID: ${txResponse.transactionId.toString()}`);
        console.log(`HashScan: https://hashscan.io/testnet/token/${tokenId.toString()}`);
        console.log(`Add to .env: HEDERA_TOKEN_ID=${tokenId.toString()}`);
    } catch (error) {
        console.error("Error creating token:", error);
        if (error.status) {
            console.error(`Status: ${error.status.toString()}`);
        }
    }
}

createToken();