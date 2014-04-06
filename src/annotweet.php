<?php

require_once(__DIR__ . '/../vendor/autoload.php');
require_once(__DIR__ . '/../config.php');

$app = new \Slim\Slim();

Twitter::$cacheDir = __DIR__ . '/cache';
Twitter::$cacheExpire = 60;

$twitter = new Twitter(
    $config['apiKey'],
    $config['apiSecret'],
    $config['accessToken'],
    $config['accessTokenSecret']
);

function json_response($data) {
    header('Content-Type: application/json', true);
    echo json_encode($data);
}

$app->get('/', function() use ($config, $twitter) {
    // $results = $twitter->load(Twitter::ME, 100, array('screen_name' => $config['accountName']));
});

$app->get('/tweets', function() use ($config, $twitter) {
    $results = $twitter->load(Twitter::ME, 100, array('screen_name' => $config['accountName']));
    json_response($results);
});

$app->get('/submit', function() use ($config) {
    echo 'form';
});

$app->post('/submit', function() use ($config, $twitter) {
    echo 'blah';
});
