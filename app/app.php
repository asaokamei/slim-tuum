<?php
use Tuum\Builder\AppBuilder;

return function(AppBuilder $builder) {

    /**
     * build Slim application.
     */
    $builder->app = new Slim\App();

    /**
     * configure with config/builder.php for Demo.
     */
    $builder->set('twig-dir', __DIR__.'/Demo/twigs');
    $builder->configure('builder');

    /**
     * import routes
     */
    $builder->execute(__DIR__.'/Demo/setup');
    $builder->execute(__DIR__.'/Demo/routes');

};
