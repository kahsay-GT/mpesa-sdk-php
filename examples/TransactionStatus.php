<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/Authentication.php'; // Include the shared Authentication.php

use Mpesa\Sdk\TransactionStatus;
use Mpesa\Sdk\Client;
use Ramsey\Uuid\Uuid;

class TransactionStatusService
{
    private string $logFilePath;
    private Client $client;

    public function __construct()
    {
        $this->logFilePath = __DIR__ . '/logs/transaction_status.log';
    }

    /**
     * Query transaction status and return the response
     * @return array
     * @throws \Exception
     */
    public function queryTransactionStatus(): array
    {
        try {
            $logger = getLogger($this->logFilePath); // From Authentication.php
            $securityCredential = getSecurityCredential(); // From Authentication.php

            $ts = new TransactionStatus($this->client); // Use the authenticated client
            $tsData = [
                'Initiator' => 'apitest',
                'SecurityCredential' => $securityCredential,
                'CommandID' => 'TransactionStatusQuery',
                'TransactionID' => 'SBBD000000',
                'PartyA' => '1020',
                'IdentifierType' => '4',
                'ResultURL' => 'https://www.myservice:8080/result',
                'QueueTimeOutURL' => 'https://www.myservice:8080/timeout',
                'Remarks' => 'Trans status',
                'Occasion' => 'Query trans status',
            ];
            $tsResponse = $ts->query($tsData);
            $logger->info("Transaction Status: " . json_encode($tsResponse));
            echo "Transaction Status Response: " . json_encode($tsResponse) . "\n";
            return $tsResponse;
        } catch (\Exception $e) {
            $logger = getLogger($this->logFilePath);
            $logger->error("Transaction Status error: " . $e->getMessage());
            echo "Transaction Status error: " . $e->getMessage() . "\n";
            throw $e;
        }
    }

    /**
     * Execute the full process: authenticate and query transaction status
     */
    public function run(): void
    {
        try {
            $this->client = getClient(); // From Authentication.php
            authenticate($this->client); // From Authentication.php
            $this->queryTransactionStatus();
        } catch (\Exception $e) {
            exit(1);
        }
    }
}

// Usage
$service = new TransactionStatusService();
$service->run();