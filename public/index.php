<?php


define("DS", DIRECTORY_SEPARATOR);
define('ROOT', dirname(__DIR__));
define('PUBLIC_PATH', ROOT . DS . 'public/');

require ROOT . DS . 'vendor' . DS . 'autoload.php';


//Setup

$memcached = new Memcached();
var_dump($memcached->add("15", 1000));
var_dump($memcached->get("15"));



//To keep it simple I'll call every step of the task here, normally those actions would go into controller

//1. Register a short-lived token on the fictional Supermetrics Social Network REST API

//$service->registerToken($email,$name);

//2. Fetch the posts of a fictional user on a fictional social platform and process their posts. You will have 1000 posts over a six month period. Show stats on the following: - Average character length of a post / month - Longest post by character length / month - Total posts split by week - Average number of posts per user / month

//3. Design the above to be generic, extendable and easy to maintain by other staff members.
