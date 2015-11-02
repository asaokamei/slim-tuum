<?php

use Psr\Http\Message\StreamInterface;
use Slim\Csrf\Guard;
use Slim\Http\Request;
use Slim\Http\Response;
use Tuum\Respond\Respond;
use Tuum\Respond\ResponseHelper;
use Tuum\Slimmed\CallableResolver;
use Tuum\Builder\AppBuilder;

/** @var $builder AppBuilder */
/** @var $app Slim\App */

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

ResponseHelper::$responseBuilder = function(StreamInterface $stream, $status, array $header) {
    $response = new Response();
    $response = $response->withStatus($status)
        ->withBody($stream);
    foreach($header as $key => $val) {
        $response = $response->withHeader($key, $val);
    }
    return $response;
};

