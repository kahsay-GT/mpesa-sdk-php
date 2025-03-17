<?php

require __DIR__ . '/../vendor/autoload.php';

use Mpesa\Sdk\Client;
use Mpesa\Sdk\Authentication;
use Mpesa\Sdk\StkPush;
use Mpesa\Sdk\Utilities\Logger;
use Ramsey\Uuid\Uuid;

// Configuration
$config = [
    'base_url' => 'https://apisandbox.safaricom.et/mpesa/stkpush/v3/processrequest',
    'consumer_key' => '0rqeFee8klD8whDdkjfwdJpfQkqtRnL8ZbxQ0Iov1M7e3SCN',
    'consumer_secret' => 'GAZyP9TxTXHv1hnE4xw0egUq7LFrt4AVB7j03UT3JNVgNUT9dc9nsNxhkShldODg',
];

// Initialize logger
$logger = new Logger(__DIR__ . '/stkpush.log');

// Initialize client
$client = new Client($config);

// Authenticate
try {
    $auth = new Authentication($client);
    $token = $auth->generateToken();
    $logger->info("Authentication successful. Access Token: " . $token->accessToken);
} catch (\Exception $e) {
    $logger->error("Authentication failed: " . $e->getMessage());
    exit(1);
}

// Process STK Push payment
try {
    $stk = new StkPush($client);
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

    // Log and display success
    $logger->info("STK Push successful: " . json_encode($response));
    echo "STK Push processed successfully!<br>";
    // echo "MerchantRequestID: " . $response['MerchantRequestID'] . "\n";
    // echo "CheckoutRequestID: " . $response['CheckoutRequestID'] . "\n";
    // echo "ResponseCode: " . $response['ResponseCode'] . "\n";
    // echo "ResponseDescription: " . $response['ResponseDescription'] . "\n";
    echo json_encode($response);
} catch (\Mpesa\Sdk\Exceptions\ValidationException $e) {
    $logger->error("Validation error: " . $e->getMessage());
    echo "Validation error: " . $e->getMessage() . "\n";
} catch (\Mpesa\Sdk\Exceptions\ApiException $e) {
    $logger->error("API error: " . $e->getMessage() . " (Code: " . $e->getCode() . ")");
    echo "API error: " . $e->getMessage() . " (Code: " . $e->getCode() . ")\n";
} catch (\Exception $e) {
    $logger->error("Unexpected error: " . $e->getMessage());
    echo "Unexpected error: " . $e->getMessage() . "\n";
}