<?php

use App\Demo\Controller\JumpController;
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
 *
 * @param ContainerInterface $c
 * @return JumpController
 */
$app->getContainer()[JumpController::class] = function(ContainerInterface $c) {
    return new JumpController($c->get(Responder::class));
};
$app->get('/jump', JumpController::class.':onGet');
$app->post('/jump', JumpController::class.':onPost');


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