<?php

use Demo\Controller\JumpController;
use Demo\Controller\UploadController;
use Demo\Controller\UploadViewer;
use Demo\Handler\CsRfMiddleware;
use Psr\Container\ContainerInterface;
use Slim\App;
use Tuum\Builder\Builder;

/** @var App $app */
/** @var Builder $builder */

$container = $app->getContainer();

/**
 * Build Tuum/Respond
 *
 * @param ContainerInterface $container
 * @return Tuum\Respond\Responder
 */
$container['responder'] = function (ContainerInterface $container) use($builder) {
    $settings = $container->get('settings')['renderer'];
    $b = new Tuum\Respond\Builder('slim3-demo');
    $b->setRenderer(
        new Tuum\Respond\Service\Renderer\Twig(
            new Twig_Environment(
                new Twig_Loader_Filesystem($settings['template_path'] . '/twigs'), [
                $builder->getVarDir() . '/twigs',
            ])
        )
    );
    $b->setContainer($container);
    $responder = new Tuum\Respond\Responder($b);
    Tuum\Respond\Respond::setResponder($responder);
    
    return $responder;
};

/**
 * setting up Monolog
 * 
 * @param ContainerInterface $c
 * @return \Monolog\Logger
 */
$container['logger'] = function (ContainerInterface $c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(
        new Monolog\Handler\FingersCrossedHandler(
            new Monolog\Handler\RotatingFileHandler($settings['path'], 2, $settings['level']),
            new Monolog\Handler\FingersCrossed\ErrorLevelActivationStrategy(Monolog\Logger::ERROR),
            0,
            true,
            true,
            Monolog\Logger::NOTICE
        )
    );
    return $logger;
};

$container[JumpController::class] = function (ContainerInterface $container) {
    return new JumpController($container->get('responder'));
};

$container[UploadController::class] = function (ContainerInterface $container) {
    return new UploadController($container->get('responder'));
};

$container[UploadViewer::class] = function (ContainerInterface $container) {
    return new UploadViewer($container->get('responder'));
};

$container[CsRfMiddleware::class] = function (ContainerInterface $container) {
    return new CsRfMiddleware($container->get('responder'));
};