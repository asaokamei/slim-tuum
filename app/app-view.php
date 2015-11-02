<?php
use Slim\Csrf\Guard;
use Slim\Http\Request;
use Slim\Http\Response;
use Tuum\Builder\AppBuilder;
use Tuum\Respond\Respond;
use Tuum\Slimmed\CallableResolver;
use Tuum\Slimmed\TuumStack;

/**
 * creating a Slim3 Application, $app.
 */

session_start();
$builder = new AppBuilder(__DIR__.'/config', dirname(__DIR__).'/var');

$app = new Slim\App();
$builder->app = $app;

$builder->configure('app-config');


/**
 * Tuum/Respond extension
 */
$app->add(
    TuumStack::forge(
        dirname(__DIR__) . '/app/views',
        'layouts/contents',
        [
            'default' => 'errors/error',
            'status'  => [
                '404' => 'errors/notFound',
                '403' => 'errors/forbidden',
            ],
            'handler' => false,
        ]
        ));

/**
 * import routes
 */
$builder->evaluate(__DIR__.'/Demo/routes');

/**
 * done construction.
 */
return $app;