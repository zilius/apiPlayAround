<?php

declare(strict_types=1);

namespace Src\App\Integrations\Supermetrics;

use Config;
use Phpfastcache\Helper\Psr16Adapter;
use Src\App\Integrations\Infrastructure\SuperMetricsClient;

class SuperMetricsService
{
    /** @var SuperMetricsClient $client */
    private SuperMetricsClient $client;

    /** @var Psr16Adapter cache */
    private Psr16Adapter $cache;

    /**
     * SuperMetricsService constructor.
     * @param Psr16Adapter $cache
     */
    public function __construct(Psr16Adapter $cache)
    {
        $this->cache = $cache;
        $this->client = new SuperMetricsClient();
    }

    /**
     * @param string $email
     * @param string $name
     * @return string
     * @throws \Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function registerToken(string $email, string $name): ?string
    {
        $cacheKey = md5($email);

        if ($this->cache->has($cacheKey)) {
            return $this->cache->get($cacheKey);
        }

        $response = $this->client->post('assignment/register',
            [
                'name' => $name,
                'email' => $email,
                'client_id' => Config::get('supermetrics.api.client_id'),
            ]);

        if (isset($response["data"]) && isset($response["data"]["sl_token"])) {

            $token = $response["data"]["sl_token"];
            $this->cache->set($cacheKey, $token, 3500); //Expire time is 1h, so 100 seconds for safety
            return $token;
        }
    }

    /**
     * @param string $token
     * @return array
     */
    public function fetchPosts(string $token): array
    {
        $posts = [];

        for($i = 1; $i < 11; $i++)
        {
            $page = $this->client->get('assignment/posts', ["page" => 1, "sl_token" => $token]);
            $posts[] = $page["data"]["posts"];
        }

        die(var_dump($posts));
    }
}