<?php

require __DIR__ . '/../vendor/autoload.php';


use Kahsaygt\Mpesa\Client;
use Kahsaygt\Mpesa\Authentication;
use Kahsaygt\Mpesa\StkPush;
use Kahsaygt\Mpesa\C2B;
use Kahsaygt\Mpesa\B2C;
use Kahsaygt\Mpesa\Utilities\Logger;
use Kahsaygt\Mpesa\Exceptions\ValidationException;
use Kahsaygt\Mpesa\Exceptions\ApiException;
use Ramsey\Uuid\Uuid; // Optional: For generating RequestRefID (install via `composer require ramsey/uuid`)

$config = [
    'base_url' => 'https://apisandbox.safaricom.et/v1/token/generate?grant_type=client_credentials',
    'consumer_key' => '0rqeFee8klD8whDdkjfwdJpfQkqtRnL8ZbxQ0Iov1M7e3SCN',
    'consumer_secret' => 'GAZyP9TxTXHv1hnE4xw0egUq7LFrt4AVB7j03UT3JNVgNUT9dc9nsNxhkShldODg',
];

$client = new Client($config);
$logger = new Logger('example.auth.log');

// Authenticate to get a Bearer token
try {
    $auth = new Authentication($client);
    $token = $auth->generateToken();
    $logger->info("Generated token: " . $token->accessToken);
    if ($token && isset($token->accessToken)) {
        echo json_encode($token);
    } 
} catch (\Exception $e) {
    $logger->error("Authentication failed: " . $e->getMessage());
    exit(1);
}

// require __DIR__ . '/../example/example.push.php';

/*
// STK Push
$stkPush = new StkPush($client);
$stkData = [
    'MerchantRequestID' => 'Partner name -aa2d64e2-aa9c-4221-be32-e5d9f0984a77',
    "BusinessShortCode"=> "1020",
    "Password"=> "M2VkZGU2YWY1Y2RhMzIyOWRjMmFkMTRiMjdjOWIwOWUxZDFlZDZiNGQ0OGYyMDRiNjg0ZDZhNWM2NTQyNTk2ZA==",
    "Timestamp"=> "20240918055823",
    "TransactionType"=> "CustomerPayBillOnline",
    "Amount"=> 20,
    "PartyA"=> "251700404709",
    "PartyB"=> "1020",
    "PhoneNumber"=> "251700404709",
    "CallBackURL"=> "https://www.myservice:8080/result",
    "AccountReference"=> "DATA",
    "TransactionDesc"=> "Payment Reason",
    'ReferenceData' => [
        [
            "Key" => "ThirdPartyReference",
            "Value" => "Ref-12345"
        ],
    ]
];
$stkResponse = $stkPush->processRequest($stkData);
$logger->info("STK Push Response: " . json_encode($stkResponse));
$logger->info("STK Push Checkout ID: " . $stkResponse->checkoutRequestId);


if ($stkResponse && isset($stkResponse->responseCode) ) {
    echo '<br><br>'.json_encode($stkResponse);
} else {
    echo "STK Push Failed";
}
*/

// C2B URL Registration
/*$c2b = new C2B($client);
$c2bResponse = $c2b->registerUrl('101020', 'http://example.com/c2b/confirmation', 'http://example.com/c2b/validation', '0rqeFee8klD8whDdkjfwdJpfQkqtRnL8ZbxQ0Iov1M7e3SCN');
$logger->info("C2B URL Registration Response: " . json_encode($c2bResponse));
if($c2bResponse){
    echo '<br><br>'.json_encode($c2bResponse);
}*/

/*
// Prepare C2B payment data (based on document example)
$c2bData = [
    'RequestRefID' => Uuid::uuid4()->toString(), // Unique request ID
    'CommandID' => 'CustomerPayBillOnline',
    'Amount' => '100',
    'AccountReference' => 'DATA',
    'Currency' => 'ETB',
    'Remark' => 'Payment for order #12345',
    'ChannelSessionID' => '10100000037656400042',
    'SourceSystem' => 'USSD',
    'Timestamp' => (new \DateTime())->format('Y-m-d\TH:i:s.vP'), // ISO 8601 format
    'Parameters' => [
        ['Key' => 'Amount', 'Value' => '500'],
        ['Key' => 'AccountReference', 'Value' => 'TU781RE'],
        ['Key' => 'Currency', 'Value' => 'ETB'],
    ],
    'ReferenceData' => [
        ['Key' => 'AppVersion', 'Value' => 'v0.2'],
    ],
    'Initiator' => [
        'IdentifierType' => 1,
        'Identifier' => '251799100026',
        'SecurityCredential' => $config['consumer_key'], // Replace with actual encrypted credential
        'SecretKey' => $config['consumer_secret'],                  // Replace with actual encrypted key
    ],
    'PrimaryParty' => [
        'IdentifierType' => 1,
        'Identifier' => '251799100026',
    ],
    'ReceiverParty' => [
        'IdentifierType' => 4,
        'Identifier' => '101020',
        'ShortCode' => '101020',
    ],
];
// Process the C2B payment
try {
    $c2b = new C2B($client);
    $response = $c2b->processPayment($c2bData);

    // Log success
    $logger->info("C2B Payment successful: " . json_encode($response));
    echo "Payment processed successfully!\n";
    echo "RequestRefID: " . $response['RequestRefID'] . "\n";
    echo "ResponseCode: " . $response['ResponseCode'] . "\n";
    echo "ResponseDesc: " . $response['ResponseDesc'] . "\n";
} catch (ValidationException $e) {
    $logger->error("Validation error: " . $e->getMessage());
    echo "Validation error: " . $e->getMessage() . "\n";
} catch (ApiException $e) {
    $logger->error("API error: " . $e->getMessage() . " (Code: " . $e->getCode() . ")");
    echo "API error: " . $e->getMessage() . " (Code: " . $e->getCode() . ")\n";
} catch (\Exception $e) {
    $logger->error("Unexpected error: " . $e->getMessage());
    echo "Unexpected error: " . $e->getMessage() . "\n";
}
*/


// B2C Payment
$b2c = new B2C($client);
$b2cData = [
    'OriginatorConversationID' => 'Partner name -'.Uuid::uuid4()->toString(), // Unique request ID,
    'InitiatorName' => 'testapi',
    'SecurityCredential' => 'your_security_credential',
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

if($b2cResponse)
 echo json_encode($b2cResponse);