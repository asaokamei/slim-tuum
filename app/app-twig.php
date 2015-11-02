<?php
use Tuum\Builder\AppBuilder;
use Tuum\Slimmed\TuumStack;

/**
 * creating a Slim3 Application, $app.
 */

/** @var AppBuilder $builder */

session_start();
$builder = new AppBuilder(__DIR__.'/config', dirname(__DIR__).'/var');

$app = new Slim\App();
$builder->app = $app;

$builder->configure('app-config');

/**
 * Tuum/Respond extension
 */
$app->add(
    TuumStack::forgeTwig(
        __DIR__ . '/twigs',
        [],
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