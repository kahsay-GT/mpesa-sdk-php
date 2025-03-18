<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/Authentication.php'; // Include the shared Authentication.php

use Mpesa\Sdk\StkPush;
use Mpesa\Sdk\Client;
use Ramsey\Uuid\Uuid;

class StkPushService
{
    private string $logFilePath;
    private Client $client;

    public function __construct()
    {
        $this->logFilePath = __DIR__ . '/logs/stkpush.log';
    }

    /**
     * Process STK Push payment and return the response
     * @return array
     * @throws \Exception
     */
    public function processPayment(): array
    {
        try {
            $logger = getLogger($this->logFilePath); // From Authentication.php

            $stk = new StkPush($this->client); // Use the authenticated client
            $stkData = [
                'MerchantRequestID' => 'Partner name -' . Uuid::uuid4()->toString(),
                'BusinessShortCode' => '1020',
                'Password' => 'M2VkZGU2YWY1Y2RhMzIyOWRjMmFkMTRiMjdjOWIwOWUxZDFlZDZiNGQ0OGYyMDRiNjg0ZDZhNWM2NTQyNTk2ZA==',
                'Timestamp' => '20240918055823',
                'TransactionType' => 'CustomerPayBillOnline',
                'Amount' => 20,
                'PartyA' => '251700404709',
                'PartyB' => '1020',
                'PhoneNumber' => '251706531263',
                'CallBackURL' => 'https://www.myservice:8080/result',
                'AccountReference' => 'Partner Unique ID',
                'TransactionDesc' => 'Payment Reason',
                'ReferenceData' => [
                    ['Key' => 'ThirdPartyReference', 'Value' => 'Ref-12345'],
                ],
            ];
            $response = $stk->processPayment($stkData);

            $logger->info("STK Push successful: " . json_encode($response));
            echo "STK Push processed successfully!\n";
            echo json_encode($response) . "\n";
            return $response;
        } catch (\Mpesa\Sdk\Exceptions\ValidationException $e) {
            $logger = getLogger($this->logFilePath);
            $logger->error("Validation error: " . $e->getMessage());
            echo "Validation error: " . $e->getMessage() . "\n";
            throw $e;
        } catch (\Mpesa\Sdk\Exceptions\ApiException $e) {
            $logger = getLogger($this->logFilePath);
            $logger->error("API error: " . $e->getMessage() . " (Code: " . $e->getCode() . ")");
            echo "API error: " . $e->getMessage() . " (Code: " . $e->getCode() . ")\n";
            throw $e;
        } catch (\Exception $e) {
            $logger = getLogger($this->logFilePath);
            $logger->error("Unexpected error: " . $e->getMessage());
            echo "Unexpected error: " . $e->getMessage() . "\n";
            throw $e;
        }
    }

    /**
     * Execute the full process: authenticate and process STK Push payment
     */
    public function run(): void
    {
        try {
            $this->client = getClient(); // From Authentication.php
            authenticate($this->client); // From Authentication.php
            $this->processPayment();
        } catch (\Exception $e) {
            exit(1);
        }
    }
}

// Usage
$service = new StkPushService();
$service->run();