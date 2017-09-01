<?php

use Demo\Controller\JumpController;
use Demo\Controller\UploadController;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Tuum\Respond\Responder;

/** @var App $app */

// Routes
$app->get('/', function (ServerRequestInterface $request, $response, $args) {
    return $this->responder->view($request, $response)->render('index', $args);
});

$app->get('/critical', function ($request, $response, $args) {
    throw new \BadMethodCallException('always throws an exception');
});

/**
 * jump and jumper to see the redirection and parameter in flash
 */
$app->any('/jump', JumpController::class);
$app->any('/upload', UploadController::class);
