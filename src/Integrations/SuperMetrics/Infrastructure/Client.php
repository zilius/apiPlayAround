<?php

declare(strict_types=1);

namespace Src\App\Integrations\Infrastructure;

use Config;
use GuzzleHttp\Client as GuzzleClient;
use Src\App\Interfaces\ClientInterface;

class Client implements ClientInterface
{

    private string $apiUrl;
    private GuzzleClient $client;

    public function __construct()
    {
        $this->apiUrl = Config::get('supermetrics.api.url');
        $this->client = new GuzzleClient();
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