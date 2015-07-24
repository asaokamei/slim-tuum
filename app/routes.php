<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Tuum\Respond\Respond;

/**
 * @var Slim\App $app
 */
$app->get('/', function (Request $request, Response $response, $args) {
    return Respond::view($request, $response)->asView('index');
});

$app->get('/jump', function ($request) {
    return Respond::view($request)
        ->asView('jump');
});

$app->post('/jumper', function ($request) {
    return Respond::redirect($request)
        ->withMessage('redirected back!')
        ->withInputData(['jumped' => 'redirected text'])
        ->withInputErrors(['jumped' => 'redirected error message'])
        ->toPath('jump');
});

$app->any('/upload', UploadController::class);

