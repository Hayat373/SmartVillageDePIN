<?php
namespace App\Services;
use Hedera\Hbar;
use Hedera\Client;
use Hedera\AccountId;
use Hedera\PrivateKey;
use Hedera\TokenCreateTransaction;
use Hedera\TokenType;
use Hedera\TokenSupplyType;
use Hedera\TopicMessageSubmitTransaction;

class HederaTokenService
{
    protected $client;

    public function __construct()
    {
        $accountId = AccountId::fromString(env('HEDERA_ACCOUNT_ID'));
        $privateKey = PrivateKey::fromString(env('HEDERA_PRIVATE_KEY'));
        $this->client = Client::forTestnet();
        $this->client->setOperator($accountId, $privateKey);
    }

    public function createVillageToken()
    {
        try {
            // Create fungible token
            $transaction = new TokenCreateTransaction()
                ->setTokenName("Village Token")
                ->setTokenSymbol("VIL")
                ->setTokenType(TokenType::FungibleCommon)
                ->setDecimals(0)
                ->setInitialSupply(1000000)
                ->setTreasuryAccountId(AccountId::fromString(env('HEDERA_ACCOUNT_ID')))
                ->setAdminKey($this->client->getOperatorPublicKey())
                ->setSupplyKey($this->client->getOperatorPublicKey())
                ->setFreezeDefault(false)
                ->setMaxTransactionFee(new Hbar(30));

            // Sign and execute transaction
            $txResponse = $transaction
                ->freezeWith($this->client)
                ->sign(PrivateKey::fromString(env('HEDERA_PRIVATE_KEY')))
                ->execute($this->client);

            // Get receipt
            $receipt = $txResponse->getReceipt($this->client);
            $tokenId = $receipt->tokenId;

            // Log token creation to HCS Topic
            $this->logToTopic("Created Village Token with Token ID: {$tokenId->toString()}");

            return [
                'tokenId' => $tokenId->toString(),
                'status' => $receipt->status->toString(),
                'transactionId' => $txResponse->transactionId->toString(),
                'hashscanUrl' => "https://hashscan.io/testnet/transaction/{$txResponse->transactionId}"
            ];
        } catch (\Exception $e) {
            \Log::error("Token creation failed: {$e->getMessage()}");
            throw $e;
        }
    }

    private function logToTopic($message)
    {
        $topicId = env('HEDERA_TOPIC_ID');
        if ($topicId) {
            $topicMessage = new TopicMessageSubmitTransaction()
                ->setTopicId(\Hedera\TopicId::fromString($topicId))
                ->setMessage($message)
                ->execute($this->client);
            \Log::info("Logged to Topic ID {$topicId}: {$message}");
        }
    }

    public static function getClient()
    {
        $accountId = AccountId::fromString(env('HEDERA_ACCOUNT_ID'));
        $privateKey = PrivateKey::fromString(env('HEDERA_PRIVATE_KEY'));
        $client = Client::forTestnet();
        $client->setOperator($accountId, $privateKey);
        return $client;
    }
}