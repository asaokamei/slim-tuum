<?php

use Slim\App;
use Slim\Csrf\Guard;
use Tuum\Builder\AppBuilder;
use Tuum\Respond\Responder;
use App\Config\Middleware\TuumStack;

/**
 * @param AppBuilder $builder
 */
return function(AppBuilder $builder) {

    /** @var App $app */
    $app = $builder->app;
    $container = $app->getContainer();

    /**
     * C.S.R.F. guardian by Slim.
     *
     * @return Guard
     */
    $app->add($container['csrf']);

    /**
     * use Tuum/Responder with Twig as renderer.
     */
    $app->add(
        new TuumStack($container->get(Responder::class))
    );

};

