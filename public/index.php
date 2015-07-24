<?php

/**
 * Step 1: Require the Slim Framework using Composer's autoloader
 *
 * If you are not using Composer, you need to load Slim Framework with your own
 * PSR-4 autoloader.
 */
use Slim\App;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use Tuum\Respond\Respond;
use Tuum\Respond\Responder;

require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . '/app/UploadController.php';

/** @var App $app */
$app = require dirname(__DIR__) . '/app/app.php';
require dirname(__DIR__) . '/app/routes.php';

/**
 * Step 4: Run the Slim application
 *
 * This method should be called last. This executes the Slim application
 * and returns the HTTP response to the HTTP client.
 */
$app->run();
