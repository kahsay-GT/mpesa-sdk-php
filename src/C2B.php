<?php

namespace Mpesa\Sdk;

use Mpesa\Sdk\Utilities\Validator;

class C2B
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Register validation and confirmation URLs for C2B transactions.
     */
    public function registerUrl(string $shortCode, string $confirmationUrl, string $validationUrl, string $apiKey): array
    {
        Validator::validate([
            'ShortCode' => $shortCode,
            'ConfirmationURL' => $confirmationUrl,
            'ValidationURL' => $validationUrl,
            'ApiKey' => $apiKey,
        ], [
            'ShortCode' => 'required|string',
            'ConfirmationURL' => 'required|url',
            'ValidationURL' => 'required|url',
            'ApiKey' => 'required|string',
        ]);

        $data = [
            'ShortCode' => $shortCode,
            'ResponseType' => 'Completed',
            'CommandID' => 'RegisterURL',
            'ConfirmationURL' => $confirmationUrl,
            'ValidationURL' => $validationUrl,
        ];

        return $this->client->request('POST', "/v1/c2b-register-url/register?apikey={$apiKey}", $data);
    }

    /**
     * Process a C2B payment.
     */
    public function processPayment(array $data): array
    {
        /*Validator::validate($data, [
            'RequestRefID' => 'required|string',
            'CommandID' => 'required|string',
            'Amount' => 'required|numeric',
            'AccountReference' => 'required|string',
            'Currency' => 'required|string',
            'Timestamp' => 'required|string',
            'ReceiverParty.Identifier' => 'required|string',
            'ReceiverParty.ShortCode' => 'required|string',
        ]);*/

        return $this->client->request('POST', '/v1/c2b/payments', $data);
    }
}