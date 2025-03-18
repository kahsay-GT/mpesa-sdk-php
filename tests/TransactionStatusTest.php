<?php

namespace Mpesa\Sdk\Tests;

use PHPUnit\Framework\TestCase;
use Mpesa\Sdk\Client;
use Mpesa\Sdk\TransactionStatus;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class TransactionStatusTest extends TestCase
{
    private function createMockClient(array $responses): Client
    {
        $mock = new MockHandler($responses);
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
        return $client;
    }

    public function testTransactionStatusQuery()
    {
        $client = $this->createMockClient([
            new Response(200, [], json_encode(['ResponseCode' => '0', 'ResponseDescription' => 'Success'])),
        ]);
        $ts = new TransactionStatus($client);
        $response = $ts->query([
            'Initiator' => 'apitest',
            'SecurityCredential' => 'test_cred',
            'CommandID' => 'TransactionStatusQuery',
            'TransactionID' => 'SBBD000000',
            'PartyA' => '1020',
            'IdentifierType' => '4',
            'ResultURL' => 'https://example.com/result',
            'QueueTimeOutURL' => 'https://example.com/timeout',
            'Remarks' => 'Test query',
        ]);
        $this->assertEquals('0', $response['ResponseCode']);
    }
}