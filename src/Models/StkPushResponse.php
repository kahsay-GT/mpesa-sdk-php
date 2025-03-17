<?php

namespace Kahsaygt\Mpesa\Models;

class StkPushResponse
{
    public string $merchantRequestId;
    public string $checkoutRequestId;
    public string $responseCode;
    public string $responseDescription;
    public string $customerMessage;
}