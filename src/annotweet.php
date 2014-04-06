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
    $config['api_key'],
    $config['api_secret'],
    $config['access_token'],
    $config['access_token_secret']
);

function json_response($data) {
    header('Content-Type: application/json', true);
    echo json_encode($data);
}

function get_tweets() {
    global $twitter, $config;
    return $twitter->load(Twitter::ME, 100);
}

$app->get('/', function() use ($config, $twig, $twitter) {
    $twig->display('index.twig', array('tweets' => get_tweets()));
});

$app->get('/tweets', function() use ($config, $twitter) {
    $results = get_tweets();
    json_response($results);
});

$app->get('/submit', function() use ($config, $twig) {
    $twig->display('form.twig');
});

$app->post('/submit', function() use ($app, $config, $twitter) {
    $tweet = $app->request->post('tweet');
    if (strlen($tweet) <= 140) {
        $twitter->send($tweet);
    }
    $app->redirect('/');
});
