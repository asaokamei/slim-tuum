<?php
use Tuum\Slimmed\CallableResolver;
use Tuum\Slimmed\CsRfGuard;
use Tuum\Slimmed\TuumStack;

/**
 * creating a Slim3 Application, $app.
 */

$app = new Slim\App();
$app->getContainer()['callableResolver'] = function($c) {
    return new CallableResolver($c);
};

$app->add(new CsRfGuard());

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
 * done construction.
 */
return $app;