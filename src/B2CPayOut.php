<?php

namespace Mpesa\Sdk;

use Mpesa\Sdk\Utilities\Validator;

class B2CPayOut
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Process a B2C payment request.
     */
    public function paymentRequest(array $data): array
    {
        Validator::validate($data, [
            'OriginatorConversationID' => 'required|string',
            'InitiatorName' => 'required|string',
            'SecurityCredential' => 'required|string',
            'CommandID' => 'required|string',
            'Amount' => 'required|numeric',
            'PartyA' => 'required|string',
            'PartyB' => 'required|string',
            'Remarks' => 'required|string',
            'QueueTimeOutURL' => 'required|url',
            'ResultURL' => 'required|url',
        ]);

        return $this->client->request('POST', '/mpesa/b2c/v2/paymentrequest', $data);
    }
}