<?php

require_once(__DIR__ . '/vendor/autoload.php');

$app = new \Slim\Slim();

$app->get('/', function() {
    echo 'hello';
});

$app->get('/twitter-feed', function() {
    echo 'twitter-feed';
});

$app->run();