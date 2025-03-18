<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/Authentication.php'; // Include the shared Authentication.php

use Mpesa\Sdk\SimulateTransaction;
use Mpesa\Sdk\Client;
use Ramsey\Uuid\Uuid;

class SimulateTransactionService
{
    private string $logFilePath;
    private Client $client;

    public function __construct()
    {
        $this->logFilePath = __DIR__ . '/logs/simulate_transaction.log';
    }

    /**
     * Simulate a C2B transaction and return the response
     * @return array
     * @throws \Exception
     */
    public function simulateTransaction(): array
    {
        try {
            $logger = getLogger($this->logFilePath); // From Authentication.php

            $simulate = new SimulateTransaction($this->client); // Use the authenticated client
            $simulateData = [
                "CommandID" => "CustomerBuyGoodsOnline", 
                "Amount" => "100",
                "Msisdn" => "251700404709",
                'BillRefNumber' => 'TEST' . Uuid::uuid4()->toString(), // Optional, made unique
                "ShortCode" => "443443"
            ];
            $simulateResponse = $simulate->simulate($simulateData);
            $logger->info("Simulate Transaction: " . json_encode($simulateResponse));
            echo "Simulate Transaction Response: " . json_encode($simulateResponse) . "\n";
            return $simulateResponse;
        } catch (\Exception $e) {
            $logger = getLogger($this->logFilePath);
            $logger->error("Simulate Transaction error: " . $e->getMessage());
            echo "Simulate Transaction error: " . $e->getMessage() . "\n";
            throw $e;
        }
    }

    /**
     * Execute the full process: authenticate and simulate transaction
     */
    public function run(): void
    {
        try {
            $this->client = getClient(); // From Authentication.php
            // authenticate($this->client); // From Authentication.php
            $this->simulateTransaction();
        } catch (\Exception $e) {
            exit(1);
        }
    }
}

// Usage
$service = new SimulateTransactionService();
$service->run();