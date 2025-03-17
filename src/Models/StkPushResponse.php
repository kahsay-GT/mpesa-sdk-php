<?php

namespace Mpesa\Sdk\Models;

class StkPushResponse
{
    public string $merchantRequestId;
    public string $checkoutRequestId;
    public string $responseCode;
    public string $responseDescription;
    public string $customerMessage;
}