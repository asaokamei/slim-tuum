<?php

/**
 * definitions for controllers and other objects.
 *
 * @var Slim\App $app
 */
use App\Demo\Controller\JumpController;
use App\Demo\Controller\UploadController;
use Interop\Container\ContainerInterface;
use Tuum\Respond\Responder;
use Tuum\Slimmed\DocumentMap;

$container = $app->getContainer();

/**
 * jump and jumper to see the redirection and parameter in flash
 *
 * @param ContainerInterface $c
 * @return JumpController
 */
$container[JumpController::class] = function(ContainerInterface $c) {
    return new JumpController($c->get(Responder::class));
};


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
