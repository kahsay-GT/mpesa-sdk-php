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

// Path to store token data (could be a database in production)
const TOKEN_STORAGE_PATH = __DIR__ . '/token.json';

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

        // Store token and expiry time
        $tokenData = [
            'accessToken' => $token->accessToken,
            'expiresAt' => time() + $token->expiresIn, // Current time + expiry time in seconds
        ];
        file_put_contents(TOKEN_STORAGE_PATH, json_encode($tokenData));

        $logger->info("Authentication successful. Access Token: " . $token->accessToken);
        $logger->info("Token Data: " . json_encode($token));
        return $token->accessToken;
    } catch (\Exception $e) {
        $logger->error("Authentication failed: " . $e->getMessage());
        throw $e;
    }
}


/**
 * Get or refresh the access token
 * @param Client $client
 * @return string Access token
 * @throws \Exception
 */
function getAccessToken(Client $client): string
{
    $logger = new Logger(__DIR__ . '/logs/authenticate.log');

    // Check if token exists and is valid
    if (file_exists(TOKEN_STORAGE_PATH)) {
        $tokenData = json_decode(file_get_contents(TOKEN_STORAGE_PATH), true);
        $currentTime = time();

        if ($tokenData && isset($tokenData['accessToken']) && $tokenData['expiresAt'] > $currentTime) {
            $logger->info("Using cached token: " . $tokenData['accessToken']);
            return $tokenData['accessToken'];
        } else {
            $logger->info("Token expired or invalid. Re-authenticating...");
        }
    } else {
        $logger->info("No token found. Authenticating...");
    }

    // Token is expired or doesn’t exist, re-authenticate
    return authenticate($client);
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


// Usage example
try {
    $client = getClient();
    $accessToken = getAccessToken($client);
    echo "Current Access Token: " . $accessToken . PHP_EOL;

    // Simulate using the token elsewhere (e.g., API call)
    // For testing, you can wait 3600 seconds and call again to see re-authentication
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
}