<?php
use Tuum\Slimmed\TuumStack;

/** @var $app Slim\App */

/**
 * use Tuum/Responder with Tuum/View as renderer.
 */
$app->add(
    TuumStack::forge(
        dirname(__DIR__) . '/views',
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

