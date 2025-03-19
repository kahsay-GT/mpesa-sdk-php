<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/Authentication.php'; // Include the shared Authentication.php

use Mpesa\Sdk\C2BValidate;
use Mpesa\Sdk\Client;
use Ramsey\Uuid\Uuid;

class C2BValidateService
{
    private string $logFilePath;
    private Client $client;

    public function __construct()
    {
        $this->logFilePath = __DIR__ . '/logs/c2bvalidate.log';
    }

    /**
     * Process STK Push payment and return the response
     * @return array
     * @throws \Exception
     */
    public function c2bValidate(string $validateBaseUrl): array
    {
        try {
            $logger = getLogger($this->logFilePath); // From Authentication.php

            $validate = new C2BValidate($this->client); // Use the authenticated client
            $validateData = [
                "RequestType" => "Validation",
                "TransactionType" => "Pay Bill",
                "TransID" => "RCH7BT9MZ1",
                "TransTime" => "20230317122839",
                "TransAmount" => "10",
                "BusinessShortCode" => "802025",
                "BillRefNumber" => "3JBMD1",
                "InvoiceNumber" => "",
                "OrgAccountBalance" => "",
                "ThirdPartyTransID" => "TXN12RTFR",
                "MSISDN" => "251700100150",
                "FirstName" => "Clement",
                "MiddleName" => "******",
                "LastName" => "******"
            ];
            $response = $validate->c2bValidate($validateData,$validateBaseUrl);

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
    public function run(string $validateBaseUrl): void
    {
        try {
            $this->client = getClient(); // From Authentication.php
            authenticate($this->client); // From Authentication.php
            $this->c2bValidate($validateBaseUrl);
        } catch (\Exception $e) {
            exit(1);
        }
    }
}

// Usage
$validateBaseUrl = "https://www.myservice:8080/validation"; // Replace it with you own
$service = new C2BValidateService();
$service->run($validateBaseUrl);