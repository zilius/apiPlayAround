<?php

declare(strict_types=1);

namespace Src\App\Integrations\Infrastructure;

use Config;
use GuzzleHttp\Client;
use Src\App\Interfaces\ClientInterface;

class SuperMetricsClient implements ClientInterface
{

    private string $apiUrl;
    private Client $client;

    public function __construct()
    {
        $this->apiUrl = Config::get('supermetrics.api.url');
        $this->client = new Client();
    }

    public function get(string $path, array $params): array
    {
        $response = $this->client->request('GET', $this->apiUrl . $path, ['query' => $params]);

        if ($response->getStatusCode() === 200) {
            return \GuzzleHttp\json_decode($response->getBody(), true);
        }
    }

    public function post(string $path, array $params): array
    {
        $response = $this->client->request('POST', $this->apiUrl . $path, ['form_params' => $params]);

        if ($response->getStatusCode() === 200) {
            return \GuzzleHttp\json_decode($response->getBody(), true);
        }
    }
}