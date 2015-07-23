<?php
use Tuum\Slimmed\TuumStack;

/**
 * creating a Slim3 Application, $app.
 */

$app = new Slim\App();

/**
 * Tuum/Respond extension
 */
$app->add(
    TuumStack::forge(
        dirname(__DIR__) . '/app/views',
        [
            'default' => 'errors/error',
            'status'  => [],
            'handler' => false,
        ],
        $_COOKIE
        ));


/**
 * done construction.
 */
return $app;