<?php

namespace Mpesa\Sdk;

use Mpesa\Sdk\Utilities\Validator;

class StkPush
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Process an STK Push payment request.
     *
     * @param array $data STK Push request data
     * @return array API response
     * @throws \XAI\Mpesa\Exceptions\ValidationException
     * @throws \XAI\Mpesa\Exceptions\ApiException
     */
    public function processPayment(array $data): array
    {
        Validator::validate($data, [
            'MerchantRequestID' => 'string', // Optional but included in your example
            'BusinessShortCode' => 'required|string|min:5|max:6',
            'Password' => 'required|string',
            'Timestamp' => 'required|string|size:14', // YYYYMMDDHHMMSS format
            'TransactionType' => 'required|string|in:CustomerPayBillOnline,CustomerBuyGoodsOnline',
            'Amount' => 'required|numeric|min:1',
            'PartyA' => 'required|string|size:12', // 12-digit MSISDN
            'PartyB' => 'required|string|min:5|max:6', // Shortcode
            'PhoneNumber' => 'required|string|size:12', // 12-digit MSISDN
            'CallBackURL' => 'required|url',
            'AccountReference' => 'required|string|max:12',
            'TransactionDesc' => 'required|string|max:13',
            'ReferenceData' => 'array', // Optional array of key-value pairs
        ]);

        // Validate ReferenceData structure if provided
        if (isset($data['ReferenceData'])) {
            foreach ($data['ReferenceData'] as $item) {
                Validator::validate($item, [
                    'Key' => 'required|string',
                    'Value' => 'required|string',
                ]);
            }
        }

        return $this->client->request('POST', '/mpesa/stkpush/v3/processrequest', $data);
    }
}