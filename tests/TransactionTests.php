<?php

namespace Mpesa\Sdk\Tests;

use PHPUnit\Framework\TestCase;
use Mpesa\Sdk\Client;
use Mpesa\Sdk\TransactionStatus;
use Mpesa\Sdk\AccountBalance;
use Mpesa\Sdk\TransactionReversal;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class TransactionTests extends TestCase
{
    private function createMockClient(array $responses): Client
    {
        $mock = new MockHandler($responses);
        $handlerStack = HandlerStack::create($mock);
        $guzzle = new GuzzleClient(['handler' => $handlerStack]);
        
        $client = new Client(['base_url' => 'https://test.api', 'consumer_key' => 'key', 'consumer_secret' => 'secret']);
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

    public function testAccountBalanceQuery()
    {
        $client = $this->createMockClient([
            new Response(200, [], json_encode(['ResponseCode' => '0', 'ResponseDescription' => 'Success'])),
        ]);
        
        $ab = new AccountBalance($client);
        $response = $ab->query([
            'Initiator' => 'apitest',
            'SecurityCredential' => 'test_cred',
            'CommandID' => 'AccountBalance',
            'PartyA' => '1020',
            'IdentifierType' => '4',
            'Remarks' => 'Balance check',
            'QueueTimeOutURL' => 'https://example.com/timeout',
            'ResultURL' => 'https://example.com/result',
        ]);
        
        $this->assertEquals('0', $response['ResponseCode']);
    }

    public function testTransactionReversal()
    {
        $client = $this->createMockClient([
            new Response(200, [], json_encode(['ResponseCode' => '0', 'ResponseDescription' => 'Success'])),
        ]);
        
        $tr = new TransactionReversal($client);
        $response = $tr->reverse([
            'Initiator' => 'apitest',
            'SecurityCredential' => 'test_cred',
            'CommandID' => 'TransactionReversal',
            'TransactionID' => 'REISKAYZC1',
            'Amount' => 200,
            'ReceiverParty' => '251700404709',
            'ReceiverIdentifierType' => '1',
            'ResultURL' => 'https://example.com/result',
            'QueueTimeOutURL' => 'https://example.com/timeout',
            'Remarks' => 'B2C Reversal',
        ]);
        
        $this->assertEquals('0', $response['ResponseCode']);
    }
}