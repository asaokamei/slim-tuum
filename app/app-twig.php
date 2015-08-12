<?php
use Tuum\Slimmed\CsRfGuard;
use Tuum\Slimmed\TuumStack;

/**
 * creating a Slim3 Application, $app.
 */

$app = new Slim\App();
$app->getContainer()['callableResolver'] = function($c) {
    return new \Tuum\Slimmed\CallableResolver($c);
};

$app->add(new CsRfGuard());

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
                '404' => 'errors/notFound',
                '403' => 'errors/forbidden',
            ],
            'handler' => false,
        ],
        $_COOKIE
        ));


/**
 * done construction.
 */
return $app;