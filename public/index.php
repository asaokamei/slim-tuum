<?php
use Slim\App;
use Tuum\Builder\AppBuilder;

/** @var App $app */

$root_dir = dirname(__DIR__);

/**
 * composer's auto-loader
 */
require_once $root_dir . '/vendor/autoload.php';

/**
 * build Slim3 application
 */
session_start();
$config = [
    'env-file' => 'env',
    'debug'    => true,
];
/** @var callable $script */
$script = require $root_dir . '/app/app.php';
$builder = $script($config);
$app = $builder->app;

/**
 * Run the Slim application
 *
 * This method should be called last. This executes the Slim application
 * and returns the HTTP response to the HTTP client.
 */
$app->run();
