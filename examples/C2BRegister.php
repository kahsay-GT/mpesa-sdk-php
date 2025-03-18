<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/Authentication.php'; // Include the shared Authentication.php

use Mpesa\Sdk\C2BRegister;
use Mpesa\Sdk\Client;

class C2BRegisterService
{
    private string $logFilePath;
    private Client $client;
    private array $inputData;

    public function __construct($data)
    {
        $this->logFilePath = __DIR__ . '/logs/c2b_register.log';
        $this->inputData = $data;
    }

    /**
     * Register C2B URLs and return the response
     * @return array
     * @throws \Exception
     */
    public function registerUrls(): array
    {
        try {
            $logger = getLogger($this->logFilePath); // From Authentication.php

            $c2b = new C2BRegister($this->client); // Use the authenticated client
            $c2bResponse = $c2b->registerUrl($this->inputData);
            $logger->info("C2B URL Registration Response: " . json_encode($c2bResponse));
            echo "C2B URL Registration Response: " . json_encode($c2bResponse) . "\n";
            return $c2bResponse;
        } catch (\Exception $e) {
            $logger = getLogger($this->logFilePath);
            $logger->error("C2B URL Registration error: " . $e->getMessage());
            echo "C2B URL Registration error: " . $e->getMessage() . "\n";
            throw $e;
        }
    }

    /**
     * Execute the full process: authenticate and register C2B URLs
     */
    public function run(): void
    {
        try {
            $this->client = getClient(); // From Authentication.php
            $this->registerUrls();
        } catch (\Exception $e) {
            exit(1);
        }
    }
}

// Usage
$data = [
    'shortCode' => '101051', // ShortCode
    'responseType' => 'Completed', // Cancelled
    'confirmationUrl' => 'http://example.com/c2b/confirmation', // ConfirmationURL
    'validationUrl' => 'http://example.com/c2b/validation', // ValidationURL
    'apiKey' => '0rqeFee8klD8whDdkjfwdJpfQkqtRnL8ZbxQ0Iov1M7e3SCN' // ConsumerKey (used as InitiatorID or similar)
];

$service = new C2BRegisterService($data);
$service->run();