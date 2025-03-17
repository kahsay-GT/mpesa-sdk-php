<?php

namespace Mpesa\Sdk;

use Mpesa\Sdk\Utilities\Validator;

class AccountBalance
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Query the account balance.
     *
     * @param array $data Account balance request data
     * @return array API response
     * @throws \XAI\Mpesa\Exceptions\ValidationException
     * @throws \XAI\Mpesa\Exceptions\ApiException
     */
    public function query(array $data): array
    {
        Validator::validate($data, [
            'OriginatorConversationID' => 'required|string',
            'Initiator' => 'required|string',
            'SecurityCredential' => 'required|string',
            'CommandID' => 'required|string',
            'PartyA' => 'required|string',
            'IdentifierType' => 'required|string',
            'Remarks' => 'required|string',
            'QueueTimeOutURL' => 'required|url',
            'ResultURL' => 'required|url',
        ]);

        return $this->client->request('POST', '/mpesa/accountbalance/v2/query', $data);
    }
}