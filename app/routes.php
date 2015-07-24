<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Tuum\Respond\Respond;

/**
 * routes
 * 
 * @var Slim\App $app
 */

/**
 * top page
 */
$app->get('/', function (Request $request, Response $response) {
    return Respond::view($request, $response)->asView('index');
});

/**
 * jump and jumper to see the redirection and parameter in flash
 */
$app->get('/jump', function ($request, Response $response) {
    return Respond::view($request, $response)
        ->asView('jump');
});

$app->post('/jumper', function (Request $request, Response $response) {
    return Respond::redirect($request, $response)
        ->withMessage('redirected back!')
        ->withInputData(['jumped' => 'redirected text'])
        ->withInputErrors(['jumped' => 'redirected error message'])
        ->toPath('jump');
});

/**
 * check asContents
 */
$app->get('/content', function(Request $request, Response $response) {
    return Respond::view($request, $response)
        ->asContents('<h1>Contents</h1><p>this is a string content in a layout file</p>');
});

/**
 * check uncaught exception
 */
$app->get('/throw', function() {
    throw new \RuntimeException('This page throws a RuntimeException!');
});

/**
 * file upload example
 */
$app->any('/upload', UploadController::class);

