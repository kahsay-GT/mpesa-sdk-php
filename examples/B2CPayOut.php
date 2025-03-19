<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/Authentication.php'; // Include the shared Authentication.php

use Mpesa\Sdk\B2CPayOut;
use Mpesa\Sdk\Client;
use Ramsey\Uuid\Uuid;

class B2CPayOutService
{
    private string $logFilePath;
    private Client $client;

    public function __construct()
    {
        $this->logFilePath = __DIR__ . '/logs/b2c_payout.log';
    }

    /**
     * Process B2C payout and return the response
     * @return array
     * @throws \Exception
     */
    public function processPayout(): array
    {
        try {
            $logger = getLogger($this->logFilePath); // From Authentication.php
            $securityCredential = getSecurityCredential(); // From Authentication.php

            $b2c = new B2CPayOut($this->client); // Use the authenticated client
            $b2cData = [
                'OriginatorConversationID' => 'Partner name -' . Uuid::uuid4()->toString(),
                'InitiatorName' => 'testapi',
                'SecurityCredential' => "M2VkZGU2YWY1Y2RhMzIyOWRjMmFkMTRiMjdjOWIwOWUxZDFlZDZiNGQ0OGYyMDRiNjg0ZDZhNWM2NTQyNTk2ZA==", // Custom credential
                'CommandID' => 'BusinessPayment',
                'Amount' => 12,
                'PartyA' => '101010',
                'PartyB' => '251700100100',
                'Remarks' => 'Test B2C',
                'QueueTimeOutURL' => 'https://example.com/b2c/timeout',
                'ResultURL' => 'https://example.com/b2c/result',
            ];
            $b2cResponse = $b2c->paymentRequest($b2cData);
            $logger->info("B2C Response: " . json_encode($b2cResponse));
            echo "B2C Response: " . json_encode($b2cResponse) . "\n";
            return $b2cResponse;
        } catch (\Exception $e) {
            $logger = getLogger($this->logFilePath);
            $logger->error("B2C Payout error: " . $e->getMessage());
            echo "B2C Payout error: " . $e->getMessage() . "\n";
            throw $e;
        }
    }

    /**
     * Execute the full process: authenticate and process B2C payout
     */
    public function run(): void
    {
        try {
            $this->client = getClient(); // From Authentication.php
            authenticate($this->client); // From Authentication.php
            $this->processPayout();
        } catch (\Exception $e) {
            exit(1);
        }
    }
}

// Usage
$service = new B2CPayOutService();
$service->run();