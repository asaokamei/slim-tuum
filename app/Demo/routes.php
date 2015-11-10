<?php

use App\Demo\Controller\JumpController;
use App\Demo\Controller\UploadController;
use Slim\Http\Request;
use Slim\Http\Response;
use Tuum\Respond\Respond;
use Tuum\Respond\Responder;
use Tuum\Slimmed\DocumentMap;

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
 * jump and jumper to see the redirection and parameter in flash
 */
$app->get('/jump', JumpController::class.':onGet');
$app->post('/jump', JumpController::class.':onPost');


/**
 * file upload example
 */
$app->any('/upload', UploadController::class);


/**
 * FileMap for Document files
 */
$app->any('/docs/{pathInfo:.*}', DocumentMap::class);