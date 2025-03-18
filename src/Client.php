<?php

namespace Mpesa\Sdk;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
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
        // Required config parameters
        $this->baseUrl = $config['base_url'] ?? 'https://apisandbox.safaricom.et';
        $this->consumerKey = $config['consumer_key'];
        $this->consumerSecret = $config['consumer_secret'];

        // Customizable parameters with defaults
        $timeout = $config['timeout'] ?? 60;              // Default timeout: 30 seconds
        $maxRetries = $config['retries'] ?? 3;            // Default retries: 3
        $retryDelay = $config['retry_delay'] ?? 3000;     // Default delay: 1 second (in milliseconds)

        // Create a handler stack for middleware
        $handlerStack = HandlerStack::create();

        // Add retry middleware
        $handlerStack->push(Middleware::retry(
            function ($retries, $request, $response, $exception) use ($maxRetries) {
                // Retry on server errors (5xx) or connection issues
                if ($retries >= $maxRetries) {
                    return false; // Stop retrying after max attempts
                }
                if ($exception instanceof RequestException && !$response) {
                    return true; // Retry on connection errors
                }
                if ($response && $response->getStatusCode() >= 500) {
                    return true; // Retry on 5xx errors
                }
                return false;
            },
            function ($retries) use ($retryDelay) {
                // Delay between retries (in milliseconds)
                return $retryDelay * $retries;
            }
        ));

        // Initialize Guzzle client with custom options
        $this->httpClient = new GuzzleClient([
            'base_uri' => $this->baseUrl,
            'timeout' => $timeout,         // Custom timeout
            // 'handler' => $handlerStack,    // Attach retry middleware
            'connect_timeout' => 60, //$config['connect_timeout'] ?? 60, // Optional: Connection timeout
            // 'http_errors' => true,         // Throw exceptions for 4xx/5xx responses
        ]);

        // $this->httpClient = new GuzzleClient([
        //     'base_uri' => $this->baseUrl,
        //     'timeout' => $config['timeout'] ?? 30,
        // ]);
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
            throw new ApiException(
                $response['resultDesc'] ?? $e->getMessage(), 
                $response['resultCode'] ?? $e->getCode()
            );
        }
    }
}