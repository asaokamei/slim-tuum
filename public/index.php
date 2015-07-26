<?php
use Slim\App;
use Tuum\Respond\Responder;

/**
 * Step 1: Require the Slim Framework using Composer's autoloader
 *
 * If you are not using Composer, you need to load Slim Framework with your own
 * PSR-4 autoloader.
 */

require dirname(__DIR__) . '/vendor/autoload.php';

/** @var App $app */
$view = 'twig';
$app  = require dirname(__DIR__) . '/app/app-twig.php';
require dirname(__DIR__) . '/app/routes.php';

/**
 * Step 4: Run the Slim application
 *
 * This method should be called last. This executes the Slim application
 * and returns the HTTP response to the HTTP client.
 */
$app->run();
