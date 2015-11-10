<?php
use Tuum\Builder\AppBuilder;

return function(AppBuilder $builder) {

    /**
     * structure settings.
     */
    $setting = $builder->configure('setting')->get('setting');
    if ($builder->debug) {
        $setting['settings']['displayErrorDetails'] = true;
    }
    $builder->set('twig-dir', __DIR__.'/Demo/twigs');

    /**
     * build Slim application for Demo.
     */
    $builder->app = new Slim\App($setting);
    $builder->configure('builder');

    /**
     * import routes
     */
    $builder->execute(__DIR__.'/Demo/setup');
    $builder->execute(__DIR__.'/Demo/routes');

};
