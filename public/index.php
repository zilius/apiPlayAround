<?php

declare(strict_types=1);

use Phpfastcache\Helper\Psr16Adapter;
use Src\App\Integrations\Supermetrics\Service;

define("DS", DIRECTORY_SEPARATOR);
define('ROOT', dirname(__DIR__));
define('PUBLIC_PATH', ROOT . DS . 'public/');

require ROOT . DS . 'vendor' . DS . 'autoload.php';
require ROOT . DS . 'src' . DS . 'Config.php';

//setup
Config::init();
$email = "zygimantas.zilevicius@gmail.com";
$name = "Zygimantas";
$cache = new Psr16Adapter('Files');
$service = new Service($cache);

//To keep it simple I'll call every step of the task here, normally those actions would go into controller

//1. Register a short-lived token on the fictional Supermetrics Social Network REST API
$token = $service->registerToken($email, $name);

//2. Fetch the posts of a fictional user on a fictional social platform and process their posts. You will have 1000 posts over a six month period. Show stats on the following: - Average character length of a post / month - Longest post by character length / month - Total posts split by week - Average number of posts per user / month

$posts = $service->fetchPosts($token);
$stats = $service->makeStatisticsHappen($posts);

dd($stats);
