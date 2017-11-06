<?php

use Psr\Http\Message\ServerRequestInterface;
use Slim\App;

/** @var App $app */

// Routes
$app->get('/', function (ServerRequestInterface $request, $response, $args) {
    return $this->responder->view($request, $response)->render('index', $args);
});
