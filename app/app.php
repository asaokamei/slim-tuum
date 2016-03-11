<?php
use Slim\App;
use Tuum\Slimmed\Container;
use Tuum\Builder\AppBuilder;

/**
 * @param array $config
 * @return App
 */
return function(array $config) {

    $root_dir = dirname(__DIR__);
    $builder = AppBuilder::forge(
        $root_dir.'/app',
        $root_dir.'/var',
        $config
    );

    /**
     * container settings.
     */
    $container = Container::forge();
    $container['twig-dir'] = __DIR__ . '/Demo/twigs';
    $container['root-dir'] = $root_dir;
    $builder->set('container', $container);
    $builder->configure('service');

    /**
     * build Slim application for Demo.
     */
    $builder->app = new Slim\App($container);
    $builder->configure('middleware');

    /**
     * import routes
     */
    $builder->execute(__DIR__.'/Demo/setting');
    $builder->execute(__DIR__.'/Demo/routes');

    return $builder->app;
};
