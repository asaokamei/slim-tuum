<?php
use Psr\Container\ContainerInterface;
use Slim\App;
use Tuum\Builder\Builder;
use Tuum\Respond\Respond;
use Tuum\Respond\Responder;
use Tuum\Respond\Responder\View;
use Tuum\Respond\Service\Renderer\Plates;
use Tuum\Respond\Service\Renderer\Twig;

/** @var App $app */
/** @var Builder $builder */

$container = $app->getContainer();

/**
 * Build Tuum/Respond
 *
 * @param ContainerInterface $container
 * @return Responder
 */
$container[Responder::class] = function (ContainerInterface $container) use($builder) {
    $b = new Tuum\Respond\Builder('slim3-demo');
    $b->setRenderer(
        new Twig(
            new Twig_Environment(
                new Twig_Loader_Filesystem($builder->getAppDir() . '/twigs'), [
                $builder->getVarDir() . '/twigs',
            ])
        )
    );
    $b->setContainer($container);
    $responder = new Responder($b);
    Respond::setResponder($responder);
    
    return $responder;
};

// Aura Session
$container['session'] = function () {
    $factory = new Aura\Session\SessionFactory();
    return $factory->newInstance($_COOKIE);
};

// view renderer
$container['renderer'] = function (ContainerInterface $c) {
    $settings = $c->get('settings')['renderer'];
    return new League\Plates\Engine($settings['template_path']);
};

// monolog
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

$container['view'] = function (ContainerInterface $c) {
    $settings = $c->get('settings')['renderer'];
    return new View(new Plates($settings['template_path']));
};