<?php

require __DIR__ . '/../vendor/autoload.php';

use Mpesa\Sdk\Client;
use Mpesa\Sdk\Authentication;
use Mpesa\Sdk\Utilities\Logger;

// Configuration
$config = [
    'base_url' => 'https://apisandbox.safaricom.et/v1/token/generate?grant_type=client_credentials',
    'consumer_key' => '0rqeFee8klD8whDdkjfwdJpfQkqtRnL8ZbxQ0Iov1M7e3SCN',
    'consumer_secret' => 'GAZyP9TxTXHv1hnE4xw0egUq7LFrt4AVB7j03UT3JNVgNUT9dc9nsNxhkShldODg',
];

$securityCredential = "lMhf0UqE4ydeEDwpUskmPgkNDZnA6NLi7z3T1TQuWCkH3/ScW8pRRnobq/AcwFvbC961+zDMgOEYGm8Oivb7L/7Y9ED3lhR7pJvnH8B1wYis5ifdeeWI6XE2NSq8X1Tc7QB9Dg8SlPEud3tgloB2DlT+JIv3ebIl/J/8ihGVrq499bt1pz/EA2nzkCtGeHRNbEDxkqkEnbioV0OM//0bv4K++XyV6jUFlIIgkDkmcK6aOU8mPBHs2um9aP+Y+nTJaa6uHDudRFg0+3G6gt1zRCPs8AYbts2IebseBGfZKv5K6Lqk9/W8657gEkrDZE8Mi78MVianqHdY/8d6D9KKhw==";

/**
 * Authenticate and return the access token
 * @param string $logFilePath Path to the log file
 * @return string Access token
 * @throws \Exception
 */
function authenticate($client): string
{
    global $config; // Access the global config
    $logger = new Logger(__DIR__ . '/logs/authenticate.log');
    // $client = getClient();

    try {
        $auth = new Authentication($client);
        $token = $auth->generateToken();
        $logger->info("Authentication successful. Access Token: " . $token->accessToken);
        return $token->accessToken;
    } catch (\Exception $e) {
        $logger->error("Authentication failed: " . $e->getMessage());
        throw $e;
    }
}

/**
 * Get the client instance
 * @return Client
 */
function getClient(): Client
{
    global $config;
    return new Client($config);
}

/**
 * Get the security credential
 * @return string
 */
function getSecurityCredential(): string
{
    global $securityCredential;
    return $securityCredential;
}

/**
 * Get a logger instance
 * @param string $logFilePath
 * @return Logger
 */
function getLogger(string $logFilePath): Logger
{
    return new Logger($logFilePath);
}

// to test othes, comment the two lines below.
// get a client
// $client = getClient();
// run authenticate
// authenticate($client);