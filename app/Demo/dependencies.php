<?php

/**
 * definitions for controllers and other objects.
 *
 * @var Slim\App $app
 */
use App\Demo\Controller\UploadController;
use App\Demo\Controller\UploadViewer;
use Interop\Container\ContainerInterface;
use Slim\App;
use Tuum\Builder\AppBuilder;
use Tuum\Respond\Responder;
use App\Config\Utils\DocumentMap;

/**
 * dependencies for Demo site.
 *
 * @param AppBuilder $builder
 */
return function(AppBuilder $builder) {

    /**
     * @var App $app
     */
    $app = $builder->app;
    $container = $app->getContainer();

    /**
     * file upload example
     *
     * @param ContainerInterface $c
     * @return UploadController
     */
    $container[UploadViewer::class] = function(ContainerInterface $c) {
        return new UploadViewer($c->get(Responder::class));
    };
    $container[UploadController::class] = function(ContainerInterface $c) {
        return new UploadController($c->get(UploadViewer::class), $c->get(Responder::class));
    };


    /**
     * FileMap for Document files
     *
     * @return DocumentMap
     */
    $container[DocumentMap::class] = function() {
        return DocumentMap::forge(dirname(__DIR__).'/docs', dirname(dirname(__DIR__)).'/vars/markUp');
    };

};
