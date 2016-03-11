<?php

/**
 * definitions for controllers and other objects.
 *
 * @var Slim\App $app
 */
use App\Demo\Controller\JumpController;
use App\Demo\Controller\UploadController;
use Interop\Container\ContainerInterface;
use Slim\App;
use Tuum\Builder\AppBuilder;
use Tuum\Respond\Responder;
use Tuum\Slimmed\DocumentMap;

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
    $container[UploadController::class] = function(ContainerInterface $c) {
        return new UploadController($c->get(Responder::class));
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
