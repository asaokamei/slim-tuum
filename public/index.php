<?php
use Slim\App;

/** @var App $app */

/**
 * composer autoloader
 */
require dirname(__DIR__) . '/vendor/autoload.php';

/**
 * build application
 */
$app = require dirname(__DIR__) . '/app/app.php';

/**
 * Run the Slim application
 *
 * This method should be called last. This executes the Slim application
 * and returns the HTTP response to the HTTP client.
 */
$app->run();
