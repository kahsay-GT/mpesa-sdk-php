<?php

namespace Kahsaygt\Mpesa\Models;

class ErrorResponse
{
    public string $resultCode;
    public string $resultDesc;
    public ?string $requestId;

    public function __construct(array $data)
    {
        $this->resultCode = $data['resultCode'] ?? $data['ResponseCode'] ?? 'unknown';
        $this->resultDesc = $data['resultDesc'] ?? $data['ResponseDesc'] ?? 'Unknown error';
        $this->requestId = $data['requestId'] ?? $data['RequestRefID'] ?? null;
    }
}