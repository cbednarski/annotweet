<?php

require_once(__DIR__ . '/../vendor/autoload.php');
require_once(__DIR__ . '/../config.php');

$app = new \Slim\Slim();

$cache_dir = __DIR__ . '/../cache';

$twig_loader = new Twig_Loader_Filesystem(__DIR__ . '/templates');
$twig = new Twig_Environment($twig_loader, array(
    // 'cache' => $cache_dir
));

Twitter::$cacheDir = $cache_dir;
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

function get_tweets() {
    global $twitter, $config;
    return $twitter->load(Twitter::ME, 100, array('screen_name' => $config['accountName']));
}

$app->get('/', function() use ($config, $twig, $twitter) {
    $twig->display('index.twig', array('tweets' => get_tweets()));
});

$app->get('/tweets', function() use ($config, $twitter) {
    $results = get_tweets();
    json_response($results);
});

$app->get('/submit', function() use ($config) {
    echo 'form';
});

$app->post('/submit', function() use ($config, $twitter) {
    echo 'blah';
});
