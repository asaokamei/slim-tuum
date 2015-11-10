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
$builder = AppBuilder::forge(
    $root_dir.'/app/config',
    $root_dir.'/var', [
        'env-file' => 'env',
        'debug'    => true,
    ]
);
$builder->setup(
    require $root_dir . '/app/app.php'
);
$app = $builder->app;

/**
 * Run the Slim application
 *
 * This method should be called last. This executes the Slim application
 * and returns the HTTP response to the HTTP client.
 */
$app->run();
