<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/Authentication.php'; // Include the shared Authentication.php

use Mpesa\Sdk\C2BRegister;
use Mpesa\Sdk\Client;

class C2BRegisterService
{
    private string $logFilePath;
    private Client $client;

    public function __construct()
    {
        $this->logFilePath = __DIR__ . '/logs/c2b_register.log';
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
            $c2bResponse = $c2b->registerUrl(
                '101050', // ShortCode
                'http://example.com/c2b/confirmation', // ConfirmationURL
                'http://example.com/c2b/validation', // ValidationURL
                '0rqeFee8klD8whDdkjfwdJpfQkqtRnL8ZbxQ0Iov1M7e3SCN' // ConsumerKey (used as InitiatorID or similar)
            );
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
            authenticate($this->client); // From Authentication.php
            $this->registerUrls();
        } catch (\Exception $e) {
            exit(1);
        }
    }
}

// Usage
$service = new C2BRegisterService();
$service->run();