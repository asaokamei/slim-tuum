<?php
use Demo\Controller\DocumentMap;
use Demo\Controller\JumpController;
use Demo\Controller\LoginController;
use Demo\Controller\LoginPresenter;
use Demo\Controller\PaginationController;
use Demo\Controller\UploadController;
use Demo\Controller\UploadViewer;
use Demo\Handler\NamedRoutes;
use Demo\Handler\RespondMiddleware;
use Psr\Container\ContainerInterface;
use Slim\App;

/** @var App $app */

$container = $app->getContainer();

/**
 * Build Tuum/Respond
 *
 * @param ContainerInterface $container
 * @return Tuum\Respond\Responder
 */
$container['responder'] = function (ContainerInterface $container) {
    $settings = $container->get('settings')['renderer'];
    $responder = \Tuum\Respond\Responder::forge(
        \Tuum\Respond\Builder::forge('slim3-demo')
            ->setRenderer(
                Tuum\Respond\Service\Renderer\Twig::forge(
                    $settings['template_path'], [
                    'cache'       => $container->get('var-dir') . '/twigs',
                    'auto_reload' => true,
                ])
            )->setNamedRoutes(
                new NamedRoutes($container['router'])
            )->setContainer($container)
    );
    // Tuum\Respond\Respond::setResponder($responder);
    
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

$container[LoginController::class] = function (ContainerInterface $container) {
    return new LoginController($container->get('responder'));
};

$container[LoginPresenter::class] = function (ContainerInterface $container) {
    return new LoginPresenter($container->get('responder'));
};

$container[UploadViewer::class] = function (ContainerInterface $container) {
    return new UploadViewer($container->get('responder'));
};

$container[RespondMiddleware::class] = function (ContainerInterface $container) {
    return new RespondMiddleware($container->get('responder'));
};

$container[DocumentMap::class] = function (ContainerInterface $container) {
    return DocumentMap::forge(
        $container->get('responder'),
        __DIR__ . '/../vendor/tuum/respond/docs',
        $container->get('var-dir') . '/md'
        );
};

$container[PaginationController::class] = function (ContainerInterface $container) {
    return new PaginationController(
        $container->get('responder'),
        new \Tuum\Pagination\Pager()
    );
};
