<?php

namespace Kahsaygt\Mpesa;

use Kahsaygt\Mpesa\Utilities\Validator;

class TransactionReversal
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Reverse a transaction.
     *
     * @param array $data Transaction reversal request data
     * @return array API response
     * @throws \XAI\Mpesa\Exceptions\ValidationException
     * @throws \XAI\Mpesa\Exceptions\ApiException
     */
    public function reverse(array $data): array
    {
        Validator::validate($data, [
            // 'OriginatorConversationID' => 'required|string',
            'Initiator' => 'required|string',
            'SecurityCredential' => 'required|string',
            'CommandID' => 'required|string',
            'TransactionID' => 'required|string',
            'Amount' => 'required|numeric',
            // "OriginalConcersationID" => 'required|string',
            // "PartyA" => 'required|string',
            'ReceiverParty' => 'required|string',
            'RecieverIdentifierType' => 'required|string',
            'ResultURL' => 'required|url',
            'QueueTimeOutURL' => 'required|url',
            'Remarks' => 'required|string',
            'Occasion' => 'string', // Optional
        ]);

        return $this->client->request('POST', '/mpesa/reversal/v2/request', $data);
    }
}