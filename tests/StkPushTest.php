<?php

namespace Mpesa\Sdk\Tests;

use PHPUnit\Framework\TestCase;
use Mpesa\Sdk\Client;
use Mpesa\Sdk\StkPush;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class StkPushTest extends TestCase
{
    private function createMockClient(array $responses): Client
    {
        $mock = new MockHandler($responses);
        $handlerStack = HandlerStack::create($mock);
        $guzzle = new GuzzleClient(['handler' => $handlerStack]);
        
        $client = new Client(['base_url' => 'https://apisandbox.safaricom.et', 'consumer_key' => 'key', 'consumer_secret' => 'secret']);
        $reflection = new \ReflectionClass($client);
        $property = $reflection->getProperty('httpClient');
        $property->setAccessible(true);
        $property->setValue($client, $guzzle);
        
        return $client;
    }

    public function testProcessPaymentSuccess()
    {
        $client = $this->createMockClient([
            new Response(200, [], json_encode([
                'MerchantRequestID' => '9cae-431a-9bb5-0e58fd6aced6',
                'CheckoutRequestID' => 'ws_CO_1202202404292020468057',
                'ResponseCode' => '0',
                'ResponseDescription' => 'Success. Request accepted for processing',
                'CustomerMessage' => 'Success. Request accepted for processing',
            ])),
        ]);
        
        $stk = new StkPush($client);
        $response = $stk->processPayment([
            'MerchantRequestID' => 'Partner name -1234',
            'BusinessShortCode' => '1020',
            'Password' => 'M2VkZGU2YWY1Y2RhMzIyOWRjMmFkMTRiMjdjOWIwOWUxZDFlZDZiNGQ0OGYyMDRiNjg0ZDZhNWM2NTQyNTk2ZA==',
            'Timestamp' => '20240918055823',
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => 20,
            'PartyA' => '251700404709',
            'PartyB' => '1020',
            'PhoneNumber' => '251700404709',
            'CallBackURL' => 'https://www.myservice:8080/result',
            'AccountReference' => 'PartnerID123',
            'TransactionDesc' => 'Test Payment',
            'ReferenceData' => [
                ['Key' => 'ThirdPartyReference', 'Value' => 'Ref-12345'],
            ],
        ]);
        
        $this->assertEquals('0', $response['ResponseCode']);
        $this->assertEquals('Success. Request accepted for processing', $response['ResponseDescription']);
    }

    public function testProcessPaymentValidationFailure()
    {
        $client = $this->createMockClient([]);
        $stk = new StkPush($client);
        
        $this->expectException(\Mpesa\Sdk\Exceptions\ValidationException::class);
        $this->expectExceptionMessage('Field Amount is required.');
        
        $stk->processPayment([
            'BusinessShortCode' => '1020',
            'Password' => 'test',
            'Timestamp' => '20240918055823',
            'TransactionType' => 'CustomerPayBillOnline',
            'PartyA' => '251700404709',
            'PartyB' => '1020',
            'PhoneNumber' => '251700404709',
            'CallBackURL' => 'https://www.myservice:8080/result',
            'AccountReference' => 'PartnerID123',
            'TransactionDesc' => 'Test Payment',
        ]);
    }
}