<?php

namespace Mpesa\Sdk;

use Mpesa\Sdk\Utilities\Validator;

class C2BValidate
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Process a B2C payment request.
     */
    public function c2bValidate(array $data, string $validateBaseUrl): array
    {
        Validator::validate($data, [
            "RequestType" => "required|string",
            "TransactionType" => "required|string",
            "TransID" => "required|string",
            "TransTime" => "required|string",
            "TransAmount" => "required|numeric",
            "BusinessShortCode" => "required|string",
            "BillRefNumber" => "required|string",
            "InvoiceNumber" => "required|string",
            "OrgAccountBalance" => "required|string",
            "ThirdPartyTransID" => "required|string",
            "MSISDN" => "required|string",
            "FirstName" => "required|string",
            "MiddleName" => "required|string",
            "LastName" => "require|string"
        ]);

        return $this->client->request('POST', $validateBaseUrl, $data);
    }
}