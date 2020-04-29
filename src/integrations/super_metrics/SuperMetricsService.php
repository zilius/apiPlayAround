<?php


use App\Interfaces\BaseClient;

class SuperMetricsService
{
    /**
     * @var BaseClient
     */
    private $client;

    public function __construct(BaseClient $client)
    {

        $this->client = $client;
    }

    public function registerToken(string $email, string $name): string
    {
        if()
    }
}