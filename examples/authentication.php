<?php
require 'vendor/autoload.php';

use Mpesa\Sdk\Client;
use Mpesa\Sdk\Authentication;

$config = [
    'base_url' => 'https://apisandbox.safaricom.et/...',
    'consumer_key' => 'your_key',
    'consumer_secret' => 'your_secret',
];

$client = new Client($config);
$auth = new Authentication($client);
$token = $auth->generateToken();
echo $token->accessToken;