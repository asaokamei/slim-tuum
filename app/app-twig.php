<?php
use Slim\Csrf\Guard;
use Slim\Http\Request;
use Slim\Http\Response;
use Tuum\Respond\Respond;
use Tuum\Slimmed\CallableResolver;
use Tuum\Slimmed\TuumStack;

/**
 * creating a Slim3 Application, $app.
 */

session_start();
$app = new Slim\App();
$app->getContainer()['callableResolver'] = function($c) {
    return new CallableResolver($c);
};
$app->getContainer()['csrf'] = function() {
    $guard = new Guard();
    $guard->setFailureCallable(function(Request $request, Response $response){
        return Respond::error($request, $response)->forbidden();
    });
    return $guard;
};

$app->add($app->getContainer()['csrf']);

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