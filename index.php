<?php

require_once(__DIR__ . '/vendor/autoload.php');
require_once(__DIR__ . '/config.php');

$app = new \Slim\Slim();
$twitter = new Twitter(
    $config['apiKey'],
    $config['apiSecret'],
    $config['accessToken'],
    $config['accessTokenSecret']
);

$app->get('/', function() {
    echo 'hello';
});

$app->get('/update-twitter-feed', function() {
    echo 'twitter-feed';
});

$app->run();