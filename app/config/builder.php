<?php

use Psr\Http\Message\StreamInterface;
use Slim\Container;
use Slim\Csrf\Guard;
use Slim\Http\Request;
use Slim\Http\Response;
use Tuum\Respond\Respond;
use Tuum\Respond\ResponseHelper;
use Tuum\Slimmed\CallableResolver;
use Tuum\Builder\AppBuilder;
use Tuum\Slimmed\DocumentMap;

/** @var $builder AppBuilder */

$app = new Slim\App();
$builder->app = $app;


/**
 * resolving found path to a resolver. 
 * 
 * @param Container $c
 * @return CallableResolver
 */
$app->getContainer()['callableResolver'] = function($c) {
    return new CallableResolver($c);
};
/**
 * C.S.R.F. guardian by Slim. 
 * 
 * @return Guard
 */
$app->getContainer()['csrf'] = function() {
    $guard = new Guard();
    $guard->setFailureCallable(function(Request $request, Response $response){
        return Respond::error($request, $response)->forbidden();
    });
    return $guard;
};

$app->add($app->getContainer()['csrf']);

/**
 * set up a response builder. 
 * 
 * @param StreamInterface $stream
 * @param int             $status
 * @param array           $header
 * @return Response
 */
ResponseHelper::$responseBuilder = function(StreamInterface $stream, $status, array $header) {
    $response = new Response();
    $response = $response
        ->withBody($stream)
        ->withStatus($status)
    ;
    foreach($header as $key => $val) {
        $response = $response->withHeader($key, $val);
    }
    return $response;
};

/**
 * set up FileMap resolver, DocumentMap. 
 * 
 * @return DocumentMap
 */
$app->getContainer()[DocumentMap::class] = function() {
    return DocumentMap::forge(dirname(__DIR__).'/docs', dirname(dirname(__DIR__)).'/vars/markUp');
};