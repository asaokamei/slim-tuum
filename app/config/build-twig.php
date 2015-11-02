<?php
use Tuum\Slimmed\TuumStack;

/** @var $app Slim\App */

/**
 * use Tuum/Responder with Twig as renderer. 
 */
$app->add(
    TuumStack::forgeTwig(
        dirname(__DIR__) . '/twigs',
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

