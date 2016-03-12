<?php
use App\Config\Define\GuardConfig;
use App\Config\Define\NotFoundFactory;
use App\Config\Define\ResponderFactory;
use Slim\DefaultServicesProvider;
use App\Config\Utils\Container;
use Tuum\Builder\AppBuilder;
use Tuum\Respond\Responder;

/**
 * settings for Slim's Container.
 *
 * @param AppBuilder $builder
 * @return Container
 */
return function(AppBuilder $builder) {

    /** @var Container $container */
    $container = Container::forge();

    $container['notFoundHandler'] = new NotFoundFactory();
    $container['csrf'] = new GuardConfig();
    $container[Responder::class] = new ResponderFactory();

    $setting = [
        'httpVersion' => '1.1',
        'responseChunkSize' => 4096,
        'outputBuffering' => 'append',
        'determineRouteBeforeAppMiddleware' => false,
        'displayErrorDetails' => false,
    ] + $builder->get('options');
    if ($builder->debug) {
        $setting['displayErrorDetails'] = true;
    }

    $container['settings'] = $setting;

    $defaults = new DefaultServicesProvider();
    /** @noinspection PhpParamsInspection */
    $defaults->register($container);

    return $container;
};
