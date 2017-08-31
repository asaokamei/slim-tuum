<?php
use Slim\App;
use Tuum\Respond\Responder;

/** @var App $app */

// Routes
$app->get('/', function ($request, $response, $args) {
    /** @var Responder $responder */
    $responder = $this->get(Responder::class);
    return $responder->view($request, $response)->render('index', $args);
});

$app->get('/critical', function ($request, $response, $args) {
    throw new \BadMethodCallException('always throws an exception');
});