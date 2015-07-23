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
require dirname(__DIR__) . '/app/SampleCtrl.php';

/**
 * Step 2: Instantiate a Slim application
 *
 * This example instantiates a Slim application using
 * its default settings. However, you will usually configure
 * your Slim application now by passing an associative array
 * of setting names and values into the application constructor.
 */
/** @var App $app */
$app = require dirname(__DIR__) . '/app/app.php';

/**
 * Step 3: Define the Slim application routes
 *
 * Here we define several Slim application routes that respond
 * to appropriate HTTP request methods. In this example, the second
 * argument for `Slim::get`, `Slim::post`, `Slim::put`, `Slim::patch`, and `Slim::delete`
 * is an anonymous function.
 */
$app->get('/', function (Request $request, Response $response, $args) {
    return $response->write('
<h1>hello Slim 3</h1>
<ul>
    <li><a href="/respond" >tuum</a></li>
</ul>
    ');
});

$app->any('/respond{pathInfo:[-/_0-9a-zA-Z]*}',
    function (Request $request, Response $response, $args) {
        return Respond::view($request, $response)
            ->with('args', $args)
            ->asView('respond');
    });

$app->get('/hello/{name}',
    function (Request $request, Response $response, $args) {
        $response->write("Hello, " . $args['name']);
        return $response;
    })->setName('hello');


/** @var Container $c */
$app->get('/class[/{pathInfo:.*}]', SampleCtrl::class.':__invoke')->setName('class');

/**
 * Step 4: Run the Slim application
 *
 * This method should be called last. This executes the Slim application
 * and returns the HTTP response to the HTTP client.
 */
$app->run();
