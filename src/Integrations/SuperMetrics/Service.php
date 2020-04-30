<?php

declare(strict_types=1);

namespace Src\App\Integrations\Supermetrics;

use Config;
use DateTime;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;
use Phpfastcache\Helper\Psr16Adapter;
use Psr\Cache\InvalidArgumentException;
use Src\App\Integrations\Infrastructure\Client;
use Src\App\Integrations\SuperMetrics\Domain\StatisticsFormatter;

class Service
{
    /** @var Client client */
    private Client $client;

    /** @var Psr16Adapter cache */
    private Psr16Adapter $cache;

    /**
     * SuperMetricsService constructor.
     * @param Psr16Adapter $cache
     */
    public function __construct(Psr16Adapter $cache)
    {

        $this->cache = $cache;
        $this->client = new Client();
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

        $statisticsFormatter = new StatisticsFormatter();

        $statsArray = [];

        $postsByMonth = $statisticsFormatter->groupMessagesByMonth($posts);

        foreach ($postsByMonth as $key => $monthsPosts) {

            $sumMsgLength = 0;
            $longestPosts = [];
            $longestPostLen = 0;
            $weekPosts = [];
            $usersWhoPosted = [];

            foreach ($monthsPosts as $monthPost) {

                $postLength = strlen($monthPost["message"]);
                $sumMsgLength += $postLength;
                $weekNumber = DateTime::createFromFormat('Y-m-d\TH:i:s+', $monthPost['created_time'])->format('W');
                $userId = $monthPost['from_id'];


                if (!isset($weekPosts[$weekNumber])) {
                    $weekPosts[$weekNumber] = 1;
                } else {
                    $weekPosts[$weekNumber]++;
                }


                if (!in_array($userId, $usersWhoPosted)) {
                    $usersWhoPosted[] = $userId;
                }

                if ($longestPostLen < $postLength) {
                    $longestPosts = [];
                    $longestPostLen = $postLength;
                    $longestPosts[] = ['id' => $monthPost["id"], 'message' => $monthPost["message"], 'length' => $postLength];
                } elseif ($longestPostLen == $postLength) {
                    $longestPosts[] = ['id' => $monthPost["id"], 'message' => $monthPost["message"], 'length' => $postLength];
                }
            }

            $statsArray[$key]["AvgPostLength"] = round($sumMsgLength / count($monthsPosts), 2);
            $statsArray[$key]["LongestPosts"] = $longestPosts;
            $statsArray[$key]["WeeklyPosts"] = $weekPosts;
            $statsArray[$key]["AvgPostsPerUser"] = round(count($monthsPosts) / count($usersWhoPosted), 2);

        }

        return $statsArray;
    }
}