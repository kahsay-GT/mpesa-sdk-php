<?php

namespace Mpesa\Sdk;

use Mpesa\Sdk\Utilities\Validator;

class C2BRegister
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Register validation and confirmation URLs for C2B transactions.
     */
    public function registerUrl(array $inputData, /*string $shortCode, string $confirmationUrl, string $validationUrl, string $apiKey*/): array
    {
        Validator::validate([
            'ShortCode' => $inputData['shortCode'],
            'ConfirmationURL' => $inputData['confirmationUrl'],
            'ValidationURL' => $inputData['validationUrl'],
            'ApiKey' => $inputData['apiKey'],
        ], [
            'ShortCode' => 'required|string',
            'ConfirmationURL' => 'required|url',
            'ValidationURL' => 'required|url',
            'ApiKey' => 'required|string',
        ]);

        $data = [
            'ShortCode' => $inputData['shortCode'],
            'ResponseType' => 'Completed',
            'CommandID' => 'RegisterURL',
            'ConfirmationURL' => $inputData['confirmationUrl'],
            'ValidationURL' => $inputData['validationUrl'],
        ];

        return $this->client->request('POST', "/v1/c2b-register-url/register?apikey={$inputData['apiKey']}", $data);
    }
}