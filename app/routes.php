<?php

use Demo\Controller\DocumentMap;
use Demo\Controller\JumpController;
use Demo\Controller\PaginationController;
use Demo\Controller\UploadController;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;

/** @var App $app */

// Routes
$app->get('/', function (ServerRequestInterface $request, $response, $args) {
    return $this->responder->view($request, $response)->render('index', $args);
});
