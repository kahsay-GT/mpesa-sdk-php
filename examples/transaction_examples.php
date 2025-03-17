<?php

require __DIR__ . '/../vendor/autoload.php';

use Mpesa\Sdk\Client;
use Mpesa\Sdk\Authentication;
use Mpesa\Sdk\TransactionStatus;
use Mpesa\Sdk\AccountBalance;
use Mpesa\Sdk\TransactionReversal;
use Mpesa\Sdk\Utilities\Logger;
use Ramsey\Uuid\Uuid;

// Configuration
$config = [
    'base_url' => 'https://apisandbox.safaricom.et/v1/token/generate?grant_type=client_credentials',
    'consumer_key' => '0rqeFee8klD8whDdkjfwdJpfQkqtRnL8ZbxQ0Iov1M7e3SCN',
    'consumer_secret' => 'GAZyP9TxTXHv1hnE4xw0egUq7LFrt4AVB7j03UT3JNVgNUT9dc9nsNxhkShldODg',
];
$securityCredential = "lMhf0UqE4ydeEDwpUskmPgkNDZnA6NLi7z3T1TQuWCkH3/ScW8pRRnobq/AcwFvbC961+zDMgOEYGm8Oivb7L/7Y9ED3lhR7pJvnH8B1wYis5ifdeeWI6XE2NSq8X1Tc7QB9Dg8SlPEud3tgloB2DlT+JIv3ebIl/J/8ihGVrq499bt1pz/EA2nzkCtGeHRNbEDxkqkEnbioV0OM//0bv4K++XyV6jUFlIIgkDkmcK6aOU8mPBHs2um9aP+Y+nTJaa6uHDudRFg0+3G6gt1zRCPs8AYbts2IebseBGfZKv5K6Lqk9/W8657gEkrDZE8Mi78MVianqHdY/8d6D9KKhw==";

$logger = new Logger(__DIR__ . '/transaction_examples.log');
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

// 1. Transaction Status Query
/*try {
    $ts = new TransactionStatus($client);
    $tsData = [
        'Initiator' => 'apitest',
        'SecurityCredential' => $securityCredential, // Replace with actual credential
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
} catch (\Exception $e) {
    $logger->error("Transaction Status error: " . $e->getMessage());
    echo "Transaction Status error: " . $e->getMessage() . "\n";
}*/

// 2. Account Balance Query
try {
    $ab = new AccountBalance($client);
    $abData = [
        'OriginatorConversationID' => 'Partner name -'.Uuid::uuid4()->toString(), // Unique request ID,
        'Initiator' => 'apitest',
        'SecurityCredential' => $config['consumer_secret'], // Replace with actual credential
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
} catch (\Exception $e) {
    $logger->error("Account Balance error: " . $e->getMessage());
    echo "Account Balance error: " . $e->getMessage() . "\n";
}

// 3. Transaction Reversal
$trData = null;
try {
    $tr = new TransactionReversal($client);
    $trData = [
        'OriginatorConversationID' => 'Partner name -'.Uuid::uuid4()->toString(), // Unique request ID,
        'Initiator' => 'apitest',
        'SecurityCredential' => $securityCredential, // Replace with actual credential
        'CommandID' => 'TransactionReversal',
        'TransactionID' => '0',
        'Amount' => 200,
        "OriginalConcersationID" => "AG-".date("Ymd")."-".Uuid::uuid4()->toString(),
        "PartyA" => "1020",
        'ReceiverParty' => '251700404709',
        'RecieverIdentifierType' => '4',
        'ResultURL' => 'https://www.myservice:8080/result',
        'QueueTimeOutURL' => 'https://www.myservice:8080/timeout',
        'Remarks' => 'B2C Reversal',
        'Occasion' => 'Layout',
    ];
    $trResponse = $tr->reverse($trData);
    $logger->info("Transaction Reversal: " . json_encode($trResponse));
    echo "Transaction Reversal Response: " . json_encode($trResponse) . "\n";
} catch (\Exception $e) {
    $logger->info("Transaction Reversal Data: " . json_encode($trData));
    $logger->error("Transaction Reversal error: " . $e->getMessage());
    echo "Transaction Reversal error: " . $e->getMessage() . "\n";
}