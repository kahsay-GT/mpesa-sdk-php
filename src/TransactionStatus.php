<?php

namespace Mpesa\Sdk;

use Mpesa\Sdk\Utilities\Validator;

class TransactionStatus
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Query the status of a transaction.
     *
     * @param array $data Transaction status request data
     * @return array API response
     * @throws \XAI\Mpesa\Exceptions\ValidationException
     * @throws \XAI\Mpesa\Exceptions\ApiException
     */
    public function query(array $data): array
    {
        Validator::validate($data, [
            'Initiator' => 'required|string',
            'SecurityCredential' => 'required|string',
            'CommandID' => 'required|string',
            'TransactionID' => 'required|string',
            'PartyA' => 'required|string',
            'IdentifierType' => 'required|string',
            'ResultURL' => 'required|url',
            'QueueTimeOutURL' => 'required|url',
            'Remarks' => 'required|string',
            'Occasion' => 'string', // Optional
        ]);

        return $this->client->request('POST', '/mpesa/transactionstatus/v1/query', $data);
    }
}