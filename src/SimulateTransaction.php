<?php

namespace Mpesa\Sdk;

use Mpesa\Sdk\Utilities\Validator;

class SimulateTransaction
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Simulate a C2B transaction.
     *
     * @param array $data C2B simulation request data
     * @return array API response
     * @throws \Mpesa\Sdk\Exceptions\ValidationException
     * @throws \Mpesa\Sdk\Exceptions\ApiException
     */
    public function simulate(array $data): array
    {
        Validator::validate($data, [
            'ShortCode' => 'required|string|min:5|max:6', // Business shortcode
            'CommandID' => 'required|string|in:CustomerPayBillOnline,CustomerBuyGoodsOnline', // Transaction type
            'Amount' => 'required|numeric|min:1', // Transaction amount
            'Msisdn' => 'required|string|size:12', // Customer phone number (12-digit MSISDN)
            'BillRefNumber' => 'string|max:20', // Optional account number/reference
        ]);

        return $this->client->request('POST', '/mpesa/b2c/simulatetransaction/v1/request', $data);
    }
}