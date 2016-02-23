<?php
use Tuum\Builder\AppBuilder;

return function(array $config) {

    $root_dir = dirname(__DIR__);
    $builder = AppBuilder::forge(
        $root_dir.'/app/Config',
        $root_dir.'/var',
        $config
    );

    /**
     * structure settings.
     */
    $builder->configure('settings');
    $setting = $builder->get('setting');
    $setting['twig-dir'] = __DIR__.'/Demo/twigs';

    /**
     * build Slim application for Demo.
     */
    $builder->app = new Slim\App($setting);
    $builder->configure('middleware');

    /**
     * import routes
     */
    $builder->execute(__DIR__.'/Demo/setting');
    $builder->execute(__DIR__.'/Demo/routes');

    return $builder;
};
