<?php
use Tuum\Slimmed\TuumStack;

/**
 * creating a Slim3 Application, $app.
 */

$app = new Slim\App();
$app->getContainer()['callableResolver'] = function($c) {
    return new \Tuum\Slimmed\CallableResolver($c);
};

/**
 * Tuum/Respond extension
 */
$app->add(
    TuumStack::forgeTwig(
        __DIR__ . '/twigs',
        [
            
        ],
        'layouts/contents',
        [
            'default' => 'errors/error',
            'status'  => [
                '404' => 'errors/notFound'
            ],
            'handler' => false,
        ],
        $_COOKIE
        ));


/**
 * done construction.
 */
return $app;