<?php

namespace Kahsaygt\Mpesa\Tests;

use PHPUnit\Framework\TestCase;
use Kahsaygt\Mpesa\Client;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Kahsaygt\Mpesa\Utilities\Logger;

class ClientTest extends TestCase
{
    public function testRequestSuccess()
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode(['access_token' => 'test_token'])),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $guzzle = new GuzzleClient(['handler' => $handlerStack]);

        $client = new Client([
            // 'base_url' => 'https://test.api',
            'consumer_key' => '0rqeFee8klD8whDdkjfwdJpfQkqtRnL8ZbxQ0Iov1M7e3SCN',
            'consumer_secret' => 'GAZyP9TxTXHv1hnE4xw0egUq7LFrt4AVB7j03UT3JNVgNUT9dc9nsNxhkShldODg',
        ]);

        $logger = new Logger('mpsesa_sdk_test.log');
        
        
        // Use reflection to set the private httpClient property for testing
        $reflection = new \ReflectionClass($client);
        $property = $reflection->getProperty('httpClient');
        $property->setAccessible(true);
        $property->setValue($client, $guzzle);

        $response = $client->request('GET', '/v1/token/generate?grant_type=client_credentials');
        $this->assertEquals(['access_token' => 'test_token'], $response);

        $logger->info(json_encode($response));
    }
}