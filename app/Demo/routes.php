<?php

use App\Demo\Controller\UploadController;
use Interop\Container\ContainerInterface;
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
 * jump and jumper to see the redirection and parameter in flash
 */
$app->get('/jump', function ($request, Response $response) {
    return Respond::view($request, $response)
        ->withReqAttribute('csrf_name', 'csrf_value')
        ->asView('jump');
});

$app->post('/jump', function (Request $request, Response $response) {
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
 * 
 * @param ContainerInterface $c
 * @return UploadController
 */
$app->getContainer()[UploadController::class] = function(ContainerInterface $c) {
    return new UploadController($c->get(Responder::class));
};
$app->get('/upload', UploadController::class.':onGet');
$app->post('/upload', UploadController::class.':onPost');


/**
 * FileMap for Document files
 *
 * @return DocumentMap
 */
$app->getContainer()[DocumentMap::class] = function() {
    return DocumentMap::forge(dirname(__DIR__).'/docs', dirname(dirname(__DIR__)).'/vars/markUp');
};
$app->any('/docs/{pathInfo:.*}', DocumentMap::class);