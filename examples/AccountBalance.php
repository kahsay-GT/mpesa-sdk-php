<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/Authentication.php'; // Include the shared Authentication.php

use Mpesa\Sdk\AccountBalance;
use Mpesa\Sdk\Client;
use Ramsey\Uuid\Uuid;

class AccountBalanceService
{
    private string $logFilePath;
    private Client $client;

    public function __construct()
    {
        $this->logFilePath = __DIR__ . '/logs/account_balance.log';
    }

    /**
     * Query account balance and return the response
     * @return array
     * @throws \Exception
     */
    public function queryAccountBalance(): array
    {
        try {
             // From Authentication.php
            $logger = getLogger($this->logFilePath); // From Authentication.php
            $securityCredential = getSecurityCredential(); // From Authentication.php
            $ab = new AccountBalance($this->client);
            $abData = [
                'OriginatorConversationID' => 'Partner name -' . Uuid::uuid4()->toString(),
                'Initiator' => 'apitest',
                'SecurityCredential' => $securityCredential,
                'CommandID' => 'AccountBalance',
                'PartyA' => '1020',
                'IdentifierType' => '4',
                'Remarks' => 'Balance check',
                'QueueTimeOutURL' => 'https://www.myservice:8080/timeout',
                'ResultURL' => 'https://www.myservice:8080/result',
            ];
            $abResponse = $ab->query($abData);
            $logger->info("Account Balance: " . json_encode($abResponse));
            echo "Account Balance Response: " . json_encode($abResponse) . "\n";
            return $abResponse;
        } catch (\Exception $e) {
            $logger = getLogger($this->logFilePath);
            $logger->error("Account Balance error: " . $e->getMessage());
            echo "Account Balance error: " . $e->getMessage() . "\n";
            throw $e;
        }
    }

    /**
     * Execute the full process: authenticate and query account balance
     */
    public function run(): void
    {
        try {
            $this->client = getClient();
            authenticate($this->client); // From Authentication.php
            $this->queryAccountBalance();
        } catch (\Exception $e) {
            exit(1);
        }
    }
}

// Usage
$service = new AccountBalanceService();
$service->run();