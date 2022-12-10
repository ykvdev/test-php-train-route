<?php

namespace app\services;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class GuzzleService
{
    private ConfigService $config;

    private Client $client;

    public function __construct(ConfigService $config)
    {
        $this->config = $config;

        $this->client = new Client(['http_errors' => $this->config->get('guzzle.http_errors')]);
    }

    public function sendGetRequest(string $url): ?string
    {
        $response = $this->client->request('GET', $url, [
            RequestOptions::ALLOW_REDIRECTS => true,
            RequestOptions::HEADERS => [
                'User-Agent' => $this->config->get('guzzle.user_agent'),
            ]
        ]);

        if($response->getStatusCode() == 404) {
            return null;
        }

        return $response->getBody()->getContents();
    }
}