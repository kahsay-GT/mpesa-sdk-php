<?php

namespace Mpesa\Sdk\Tests;

use PHPUnit\Framework\TestCase;
use Mpesa\Sdk\Client;
use Mpesa\Sdk\Authentication;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Mpesa\Sdk\Utilities\Logger;

class AuthenticationTest extends TestCase
{
    public function testGenerateToken()
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode([
                'access_token' => 'test_token',
                'token_type' => 'Bearer',
                'expires_in' => '3599',
            ])),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $guzzle = new GuzzleClient(['handler' => $handlerStack]);

        $client = new Client([
            // 'base_url' => 'https://test.api',
            'consumer_key' => '0rqeFee8klD8whDdkjfwdJpfQkqtRnL8ZbxQ0Iov1M7e3SCN',
            'consumer_secret' => 'GAZyP9TxTXHv1hnE4xw0egUq7LFrt4AVB7j03UT3JNVgNUT9dc9nsNxhkShldODg',
        ]);

        $reflection = new \ReflectionClass($client);
        $property = $reflection->getProperty('httpClient');
        $property->setAccessible(true);
        $property->setValue($client, $guzzle);

        $auth = new Authentication($client);
        $token = $auth->generateToken();

        $logger = new Logger('mpsesa_sdk_auth_test.log');

        $this->assertEquals('test_token', $token->accessToken);
        $this->assertEquals('Bearer', $token->tokenType);
        $this->assertEquals('3599', $token->expiresIn);

        $logger->info(json_encode($token));

    }
}