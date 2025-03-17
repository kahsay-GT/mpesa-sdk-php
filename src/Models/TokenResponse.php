<?php

namespace Mpesa\Sdk\Models;

class TokenResponse
{
    public string $accessToken;
    public string $tokenType;
    public string $expiresIn;
}