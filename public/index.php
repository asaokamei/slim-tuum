<?php
use Tuum\Builder\Builder;

if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
    if (preg_match('/favicon.ico$/', $file)) {
        return false;
    }
}
require __DIR__ . '/../vendor/autoload.php';
session_start();

// Instantiate the app
$builder = Builder::forge(
    dirname(__DIR__) . '/app',
    dirname(__DIR__) . '/var',
    false
);
$builder->loadEnv();
$builder->load('settings');
$builder->load('app');
$builder->load('dependencies');
$builder->load('middleware');
$builder->load('routes');

/** @var \Slim\App $app */
$app = $builder->getApp();

// Run app
$app->run();