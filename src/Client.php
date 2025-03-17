<?php

namespace Mpesa\Sdk;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;
use Mpesa\Sdk\Exceptions\ApiException;

class Client
{
    private GuzzleClient $httpClient;
    private string $baseUrl;
    private string $consumerKey;
    private string $consumerSecret;
    private ?string $accessToken = null;

    public function __construct(array $config)
    {
        $this->baseUrl = $config['base_url'] ?? 'https://apisandbox.safaricom.et';
        $this->consumerKey = $config['consumer_key'];
        $this->consumerSecret = $config['consumer_secret'];
        $this->httpClient = new GuzzleClient([
            'base_uri' => $this->baseUrl,
            'timeout' => $config['timeout'] ?? 30,
        ]);
    }

    public function setAccessToken(string $token): void
    {
        $this->accessToken = $token;
    }

    public function request(string $method, string $endpoint, array $data = []): array
    {
        try {
            $options = [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => $this->accessToken ? "Bearer {$this->accessToken}" : "Basic " . base64_encode("{$this->consumerKey}:{$this->consumerSecret}"),
                ],
            ];

            if (!empty($data)) {
                $options['json'] = $data;
            }

            $response = $this->httpClient->request($method, $endpoint, $options);
            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            $response = $e->hasResponse() ? json_decode($e->getResponse()->getBody()->getContents(), true) : [];
            throw new ApiException($response['resultDesc'] ?? $e->getMessage(), $response['resultCode'] ?? $e->getCode());
        }
    }
}