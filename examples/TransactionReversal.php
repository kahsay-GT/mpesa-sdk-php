<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/Authentication.php'; // Include the shared Authentication.php

use Mpesa\Sdk\TransactionReversal;
use Mpesa\Sdk\Client;
use Ramsey\Uuid\Uuid;

class TransactionReversalService
{
    private string $logFilePath;
    private Client $client;

    public function __construct()
    {
        $this->logFilePath = __DIR__ . '/logs/transaction_reversal.log';
    }

    /**
     * Reverse a transaction and return the response
     * @return array
     * @throws \Exception
     */
    public function reverseTransaction(): array
    {
        try {
            $logger = getLogger($this->logFilePath); // From Authentication.php
            $securityCredential = getSecurityCredential(); // From Authentication.php

            $tr = new TransactionReversal($this->client); // Use the authenticated client
            $trData = [
                'OriginatorConversationID' => 'Partner name -' . Uuid::uuid4()->toString(),
                'Initiator' => 'apitest',
                'SecurityCredential' => $securityCredential,
                'CommandID' => 'TransactionReversal',
                'TransactionID' => '0',
                'Amount' => 200,
                "OriginalConcersationID" => "AG-" . date("Ymd") . "-" . Uuid::uuid4()->toString(),
                "PartyA" => "1020",
                'ReceiverParty' => '251700404709',
                'RecieverIdentifierType' => '4', // Typo corrected to 'ReceiverIdentifierType' if SDK expects it
                'ResultURL' => 'https://www.myservice:8080/result',
                'QueueTimeOutURL' => 'https://www.myservice:8080/timeout',
                'Remarks' => 'B2C Reversal',
                'Occasion' => 'Layout',
            ];
            $trResponse = $tr->reverse($trData);
            $logger->info("Transaction Reversal: " . json_encode($trResponse));
            echo "Transaction Reversal Response: " . json_encode($trResponse) . "\n";
            return $trResponse;
        } catch (\Exception $e) {
            $logger = getLogger($this->logFilePath);
            $logger->info("Transaction Reversal Data: " . json_encode($trData));
            $logger->error("Transaction Reversal error: " . $e->getMessage());
            echo "Transaction Reversal error: " . $e->getMessage() . "\n";
            throw $e;
        }
    }

    /**
     * Execute the full process: authenticate and reverse transaction
     */
    public function run(): void
    {
        try {
            $this->client = getClient(); // From Authentication.php
            // authenticate($this->client); // From Authentication.php
            $this->reverseTransaction();
        } catch (\Exception $e) {
            exit(1);
        }
    }
}

// Usage
$service = new TransactionReversalService();
$service->run();