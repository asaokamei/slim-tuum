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

/**
 * jump and jumper to see the redirection and parameter in flash
 *
 * @param ContainerInterface $c
 * @return JumpController
 */
$app->getContainer()[JumpController::class] = function(ContainerInterface $c) {
    return new JumpController($c->get(Responder::class));
};


/**
 * file upload example
 *
 * @param ContainerInterface $c
 * @return UploadController
 */
$app->getContainer()[UploadController::class] = function(ContainerInterface $c) {
    return new UploadController($c->get(Responder::class));
};


/**
 * FileMap for Document files
 *
 * @return DocumentMap
 */
$app->getContainer()[DocumentMap::class] = function() {
    return DocumentMap::forge(dirname(__DIR__).'/docs', dirname(dirname(__DIR__)).'/vars/markUp');
};
