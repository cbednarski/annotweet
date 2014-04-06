<?php

require_once(__DIR__ . '/../src/annotweet.php');

// For php's built-in web server. You may want to delete it in production.
if (preg_match('/\.(?:png|jpg|jpeg|gif|css|js)$/', $_SERVER["REQUEST_URI"])) {
    return false;    // serve the requested resource as-is.
}

$app->run();