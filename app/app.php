<?php
use Slim\App;
use App\Config\Utils\Container;
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
     * @var Container $container
     */
    $container = $builder->configure('setting');

    /**
     * build Slim application for Demo.
     */
    $builder->app = new Slim\App($container);
    $builder->configure('middleware');

    /**
     * import routes
     */
    $builder->execute(__DIR__.'/Demo/dependencies');
    $builder->execute(__DIR__.'/Demo/routes');

    return $builder->app;
};
