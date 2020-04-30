<?php

declare(strict_types=1);

namespace Src\App\Integrations\Supermetrics;

use Config;
use DateTime;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;
use Phpfastcache\Helper\Psr16Adapter;
use Psr\Cache\InvalidArgumentException;
use Src\App\Integrations\Infrastructure\SuperMetricsClient;

class SuperMetricsService
{
    /** @var SuperMetricsClient client */
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
     * @throws PhpfastcacheSimpleCacheException
     * @throws InvalidArgumentException
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

        for ($i = 1; $i < 11; $i++) {

            $page = $this->client->get('assignment/posts', ["page" => $i, "sl_token" => $token]);
            $pagePosts = $page['data']['posts'];
            $posts = array_merge($posts, $pagePosts);
        }

        return $posts;
    }

    /**
     * @param array $posts
     * @return array
     */
    public function makeStatisticsHappen(array $posts): array
    {
        $postsByMonth = [];
        $statsArray = [];

        //assign each post to each month
        foreach ($posts as $post) {

            $time = DateTime::createFromFormat('Y-m-d\TH:i:s+', $post['created_time'])->format('Y-m');
            $postsByMonth[$time][] = $post;
        }

        foreach ($postsByMonth as $key => $monthsPosts) {

            $sumMshLength = 0;
            $longestPosts = [];
            $longestPostLen = 0;
            $weekPosts = [];
            $userPostCount = [];

            foreach ($monthsPosts as $monthPost) {
                $postLength = strlen($monthPost["message"]);
                $sumMshLength += $postLength;
                $weekNumber = DateTime::createFromFormat('Y-m-d\TH:i:s+', $monthPost['created_time'])->format('W');

                if (!isset($weekPosts[$weekNumber])) {
                    $weekPosts[$weekNumber] = 1;
                }
                else
                {
                    $weekPosts[$weekNumber]++;
                }

                if ($longestPostLen < $postLength) {
                    $longestPosts = [];
                    $longestPostLen = $postLength;
                    $longestPosts[] = ['id' => $monthPost["id"], 'message' => $monthPost["message"], 'length' => $postLength];
                } elseif ($longestPostLen == $postLength) {
                    $longestPosts[] = ['id' => $monthPost["id"], 'message' => $monthPost["message"], 'length' => $postLength];
                }
            }


            $statsArray[$key]["AvgPostLength"] = round($sumMshLength / count($monthsPosts), 2);
            $statsArray[$key]["LongestPosts"] = $longestPosts;
            $statsArray[$key]["WeeklyPosts"] = $weekPosts;
            $statsArray[$key]["UserPostsCount"] = $userPostCount;

        }

        return $statsArray;
    }
}