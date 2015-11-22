<?php

use Slim\Csrf\Guard;
use Slim\Http\Request;
use Slim\Http\Response;
use Tuum\Respond\Helper\ResponderBuilder;
use Tuum\Respond\Respond;
use Tuum\Respond\Responder;
use Tuum\Respond\Service\ErrorView;
use Tuum\Respond\Service\SessionStorage;
use Tuum\Respond\Service\TwigViewer;
use Tuum\Slimmed\TuumStack;

$container = $app->getContainer();

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
 * use Tuum/Responder with Twig as renderer.
 */
$container[Responder::class] = function() use($builder) {

    $stream    = TwigViewer::forge($builder->get('twig-dir'), []);
    $errors    = ErrorView::forge($stream, [
        'default' => 'errors/error',
        'status'  => [
            '404' => 'errors/notFound',
            '403' => 'errors/forbidden',
        ],
    ]);

    return ResponderBuilder::withServices($stream, $errors, 'layouts/contents')
        ->withSession(SessionStorage::forge('slim-tuum'));
};

$app->add(
    new TuumStack($container->get(Responder::class))
);

