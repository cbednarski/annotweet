<?php

require_once(__DIR__ . '/../vendor/autoload.php');
require_once(__DIR__ . '/../config.php');

$app = new \Slim\Slim();

if (!is_dir($config['cache_dir'])) {
    mkdir($config['cache_dir'], 0755);
}

$twig_loader = new Twig_Loader_Filesystem(__DIR__ . '/templates');
$twig = new Twig_Environment($twig_loader, array(
    // 'cache' => $cache_dir
));
$twig->addGlobal('base_url', $config['base_url']);

Twitter::$cacheDir = $config['cache_dir'];
Twitter::$cacheExpire = 60;

$twitter = new Twitter(
    $config['api_key'],
    $config['api_secret'],
    $config['access_token'],
    $config['access_token_secret']
);

function get_twitter_cache($cache_dir)
{
    $files = scandir($cache_dir, SCANDIR_SORT_ASCENDING);
    foreach($files as $file) {
        if(substr($file, 0, 8) === 'twitter.') {
            return $cache_dir . '/' . $file;
        }
    }
    return null;
}

function prepend_cache($cache_dir, $item)
{
    $cache_file = get_twitter_cache($cache_dir);
    if ($cache_file) {
        $cache = json_decode(file_get_contents($cache_file));
    } else {
        $cache = array();
    }
    array_unshift($cache, $item);
    file_put_contents($cache_file, json_encode($cache));
}

function json_response($data)
{
    header('Content-Type: application/json', true);
    echo json_encode($data);
}

function get_tweets()
{
    global $twitter;
    return $twitter->load(Twitter::ME, 100);
}

$app->get('/', function() use ($app, $config) {
    $app->redirect($config['base_url'] . '/submit');
});

$app->get('/tweets', function() use ($config, $twig, $twitter) {
    $twig->display('wall.twig', array('tweets' => get_tweets()));
});

$app->get('/tweets-data', function() use ($config, $twitter) {
    $results = get_tweets();
    json_response($results);
});

$app->get('/submit', function() use ($config, $twig) {
    $twig->display('form.twig');
});

$app->post('/submit', function() use ($app, $config, $twitter, $twig) {
    $tweet = $app->request->post('tweet');
    if (strlen($tweet) <= 140) {
        try {
            $response = $twitter->send($tweet);
            if (property_exists($response, 'text') && $response->text === $tweet) {
                prepend_cache($config['cache_dir'], $response);
            }
        } catch(TwitterException $e) {
            $twig->display('form.twig', array('error' => $e->getMessage()));
        }
    }
    $app->redirect($config['base_url'] . '/submit');
});
