<?php

use App\Demo\Controller\JumpController;
use App\Demo\Controller\UploadController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use Tuum\Builder\AppBuilder;
use Tuum\Respond\Respond;
use Tuum\Respond\Responder;
use Tuum\Slimmed\DocumentMap;

/**
 * routes.
 *
 * @param AppBuilder $builder
 */
return function(AppBuilder $builder) {

    /**
     * routes
     *
     * @var Slim\App $app
     */
    $app = $builder->app;

    /**
     * top page
     */
    $app->get('/', function (ServerRequestInterface $request, ResponseInterface $response) {
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
    $app->group( '/jump', function() {
        /** @var App $this */
        $this->get('', JumpController::class.':onGet');
        $this->post('', JumpController::class.':onPost');
    })->add(function(Request $request, Response $response, $next) {
        return $next($request, $response);
    });


    /**
     * file upload example
     */
    $app->any('/upload', UploadController::class);


    /**
     * FileMap for Document files
     */
    $app->any('/docs/{pathInfo:.*}', DocumentMap::class);

};

