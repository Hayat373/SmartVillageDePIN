// create-user-account.js
const { Client, PrivateKey, AccountCreateTransaction } = require('@hashgraph/sdk');
require('dotenv').config();

async function createUserAccount() {
    const client = Client.forTestnet();
    const privateKey = PrivateKey.fromStringECDSA(process.env.HEDERA_PRIVATE_KEY);
    client.setOperator(process.env.HEDERA_ACCOUNT_ID, privateKey);

    try {
        const newKey = PrivateKey.generateECDSA();
        const tx = await new AccountCreateTransaction()
            .setKey(newKey)
            .setInitialBalance(10)
            .execute(client);
        const receipt = await tx.getReceipt(client);
        console.log('New User Account ID:', receipt.accountId.toString());
        console.log('New Private Key:', newKey.toStringRaw()); // Raw ECDSA key
    } catch (error) {
        console.error('Account Creation Error:', error.message);
    }
}

createUserAccount();