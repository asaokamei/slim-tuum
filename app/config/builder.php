<?php

use Psr\Http\Message\StreamInterface;
use Slim\Container;
use Slim\Csrf\Guard;
use Slim\Http\Request;
use Slim\Http\Response;
use Tuum\Respond\Respond;
use Tuum\Respond\Responder;
use Tuum\Respond\ResponseHelper;
use Tuum\Respond\Service\ErrorView;
use Tuum\Respond\Service\TwigStream;
use Tuum\Slimmed\CallableResolver;
use Tuum\Slimmed\TuumStack;

$container = $app->getContainer();

/**
 * resolving found path to a resolver. 
 * 
 * @param Container $c
 * @return CallableResolver
 */
$container['callableResolver'] = function($c) {
    return new CallableResolver($c);
};

/**
 * C.S.R.F. guardian by Slim. 
 * 
 * @return Guard
 */
$container['csrf'] = function() {
    $guard = new Guard();
    $guard->setFailureCallable(function(Request $request, Response $response){
        return Respond::error($request, $response)->forbidden();
    });
    return $guard;
};

$app->add($container['csrf']);

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
        /** @noinspection PhpUndefinedMethodInspection  ...but why? */
        $response = $response->withHeader($key, $val);
    }
    return $response;
};

/**
 * use Tuum/Responder with Twig as renderer.
 */
$container[Responder::class] = function() use($builder) {

    $stream = TwigStream::forge($builder->get('twig-dir'), []);
    $errors = ErrorView::forge($stream, [
        'default' => 'errors/error',
        'status'  => [
            '404' => 'errors/notFound',
            '403' => 'errors/forbidden',
        ],
        'handler' => false,
    ]);
    $responder = Responder::build($stream, $errors, 'layouts/contents');

    return $responder;
};

$app->add(
    new TuumStack($container->get(Responder::class))
);

