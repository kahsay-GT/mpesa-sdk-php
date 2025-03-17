<?php

namespace Mpesa\Sdk;

use Mpesa\Sdk\Models\TokenResponse;

class Authentication
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function generateToken(): TokenResponse
    {
        $response = $this->client->request('GET', '/v1/token/generate?grant_type=client_credentials', [
            'query' => ['grant_type' => 'client_credentials'],
        ]);

        $token = new TokenResponse();
        $token->accessToken = $response['access_token'];
        $token->tokenType = $response['token_type'];
        $token->expiresIn = $response['expires_in'];

        $this->client->setAccessToken($token->accessToken);
        return $token;
    }
}